<?php

namespace App\Services;

use DateTime;
use Throwable;
use App\Models\Academy;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\EducationInstitution;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AcademyStatisticsService
{
    private const PROPERTY_NAMES = ['positions', 'education_institution', 'gender', 'course', 'status', 'application_date'];

    /**
     * @param int|null $academyId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getStatByPositions($academyId)
    {
        $prop = self::PROPERTY_NAMES[0];
        $propResponseName = 'position';
        $stat =  self::getStatByProperty($academyId, $prop, $propResponseName);
        return response()->json($stat, 200);
    }
    /**
     * @param int|null $academyId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getStatByEducationInstitutions($academyId)
    {
        $prop = self::PROPERTY_NAMES[1];
        $stat =  self::getStatByProperty($academyId, $prop);
        return response()->json($stat, 200);
    }
    /**
     * @param int|null $academyId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getStatByGenders($academyId)
    {
        $prop = self::PROPERTY_NAMES[2];
        $stat = self::getStatByProperty($academyId, $prop, isPropertyRelation: false);
        return response()->json($stat, 200);
    }
    /**
     * @param int|null $academyId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getStatByCourses($academyId)
    {
        $prop = self::PROPERTY_NAMES[3];
        $stat =  self::getStatByProperty($academyId, $prop, isPropertyRelation: false);
        return response()->json($stat, 200);
    }
    /**
     * @param int|null $academyId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getStatByStatuses($academyId)
    {
        $prop = self::PROPERTY_NAMES[4];
        $stat = self::getStatByProperty($academyId, $prop, isPropertyRelation: false);
        return response()->json($stat, 200);
    }
    /**
     * @param int|null $academyId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getStatByApplicationDate($academyId)
    {
        $prop = self::PROPERTY_NAMES[5];
        $stat = self::getStatByProperty($academyId, $prop, isPropertyRelation: false);
        return response()->json($stat, 200);
    }

    public static function getStatByMonth($monthNumber, $academyId)
    {

        $filterData = ['name' => 'month', 'month_number' => $monthNumber];



        $filteredCountStat = self::getStatByFilteredCount($academyId,$filterData);
        $courseProp = self::PROPERTY_NAMES[3];
        $genderProp = self::PROPERTY_NAMES[2];
        $eduProp = self::PROPERTY_NAMES[1];
        $statByCourse =  self::getStatByProperty($academyId, $courseProp, isPropertyRelation: false, candidateFilterData: $filterData);
        $statByGender =  self::getStatByProperty($academyId, $genderProp, isPropertyRelation: false, candidateFilterData: $filterData);
        $statByEdu =  self::getStatByProperty($academyId, $eduProp, isPropertyRelation: true, candidateFilterData: $filterData);

         $aggregateStat = self::initAggregateStat($filteredCountStat, 'count');
         $aggregateStat = self::aggregateStat($aggregateStat,$statByCourse, $courseProp);
         $aggregateStat = self::aggregateStat($aggregateStat, $statByGender, $genderProp);
         $aggregateStat = self::aggregateStat($aggregateStat, $statByEdu, $eduProp);
        return response()->json($aggregateStat, 200);

    }
    /**
     * @param int $academyId
     * @param array $filterData
     * 
     * @return array
     */
    public static function getStatByFilteredCount($academyId, $filterData)
    {
        $stat = [];
        if ($academyId == null) {
            foreach (Academy::all() as $academy) {
                $candidates =  $academy->candidates()->get();
                $candidatesCount = self::getFilteredCandidates($candidates, $filterData)->count();
                $filteredCountstat = ['academy' => $academy, 'statistic' => ['candidates_count' => $candidatesCount]];
                array_push($stat, $filteredCountstat);
            }
        } else {
            try {
                $academy = Academy::findOrFail($academyId);
            } catch (Throwable $e) {
                //Rethrown in order to be catched by handler
                throw new NotFoundHttpException(message: $e->getMessage(), code: 404);
            }
            $candidates =  $academy->candidates()->get();
            $candidatesCount = self::getFilteredCandidates($candidates, $filterData)->count();
            $filteredCountstat = ['academy' => $academy, 'statistic' => ['candidates_count' => $candidatesCount]];
            array_push($stat, $filteredCountstat);
        }
        return $stat;
    }
    /**
     * @param array $academiesStat
     * @param string $statPropName
     * 
     * @return array
     */
    private static function initAggregateStat($academiesStat, $statPropName)
    {
        foreach ($academiesStat as &$acStat) {
            $acStat[$statPropName . '_statistic'] = $acStat['statistic'];
            unset($acStat['statistic']);
        }
        return $academiesStat;
    }

    /**
     * @param array $aggregatedStat
     * @param array $academiesStat
     * @param string $statPropName
     * 
     * @return array
     */
    private static function aggregateStat($aggregatedStat, $academiesStat, $statPropName)
    {
        foreach ($academiesStat as $acStat) {
            foreach ($aggregatedStat as &$aggAcStat) {
                if ($acStat['academy']->name == $aggAcStat['academy']->name) {
                    $aggAcStat[$statPropName . '_statistic'] = $acStat['statistic'];
                    break;
                }
            }
        }
        return $aggregatedStat;
    }


    /**
     * @param Academy $academy
     * @param string $prop
     * 
     * @return \Illuminate\Support\Collection|array
     */
    public static function getPropElementCount($academy, $prop)
    {
        // Positions count
        if ($prop == self::PROPERTY_NAMES[0]) {
            $acPos = $academy->positions()->get();
            $positionsCount = $acPos->mapWithKeys(fn ($pos) => [$pos->name => 0]);
            return $positionsCount;
        }
        //Education institutions count
        if ($prop == self::PROPERTY_NAMES[1]) {
            $eduCount = EducationInstitution::all()->mapWithKeys(fn ($eduProp) => [$eduProp->name => 0]);
            return $eduCount;
        }
        //Gender count
        if ($prop == self::PROPERTY_NAMES[2]) {
            $genderCount = [];
            foreach (Candidate::GENDERS as $genderProp) {
                $genderCount += [$genderProp => 0];
            }
            return $genderCount;
        }
        //Course count
        if ($prop == self::PROPERTY_NAMES[3]) {
            $courseCount = [];
            foreach (Candidate::COURSES as $courseProp) {
                $courseCount += [$courseProp => 0];
            }
            return $courseCount;
        }
        //Statuses count
        if ($prop == self::PROPERTY_NAMES[4]) {
            $statusCount = [];
            foreach (Candidate::STATUSES as $status) {
                $statusCount += [$status => 0];
            }
            return $statusCount;
        }
        //Application dates count
        if ($prop == self::PROPERTY_NAMES[5]) {
            $appDatesCount = [];
            $candidates = $academy->candidates()->get();
            foreach ($candidates as $candidate) {
                $appDate = $candidate->application_date;
                $appDatesCount += [$appDate => 0];
            }
            return $appDatesCount;
        }
    }
    /**
     * @param \Illuminate\Support\Collection $candidates
     * @param array $filterData
     * 
     * @return \Illuminate\Support\Collection
     */
    public static function getFilteredCandidates($candidates, $filterData)
    {
        $filterName = $filterData['name'];
        $filteredCandidates = new Collection();
        if ($filterName == 'month') {
            $filterMonth = $filterData['month_number'];
            foreach ($candidates as $candidate) {
                $appDate = $candidate->application_date;
                $month = date("n", strtotime($appDate));
                if ($month == $filterMonth) {
                    $filteredCandidates->add($candidate);
                }
            }
        }
        return $filteredCandidates;
    }

    /**
     * @param int|null $academyId
     * @param string $prop
     * @param null|string $propResponseName
     * @param bool $isPropertyRelation
     * 
     * @return array
     */
    public static function getStatByProperty($academyId, $prop, $propResponseName = null, $isPropertyRelation = true, $candidateFilterData = null)
    {
        if ($propResponseName == null) {
            $propResponseName = $prop;
        }
        if ($academyId == null) {
            $academies = Academy::all();
            $academiesStat = [];
            foreach ($academies as $academy) {
                $academyStat = self::getAcademyStatByProp($academy, $prop, $propResponseName, $isPropertyRelation, $candidateFilterData);
                array_push($academiesStat, $academyStat);
            }
            return $academiesStat;
        } else {
            try {
                $academy = Academy::findOrFail($academyId);
            } catch (Throwable $e) {
                //Rethrown in order to be catched by handler
                throw new NotFoundHttpException(message: $e->getMessage(), code: 404);
            }
            $academyStat = self::getAcademyStatByProp($academy, $prop, $propResponseName, $isPropertyRelation, $candidateFilterData);
            return [$academyStat];
        }
    }


    /**
     * @param \Illuminate\Support\Collection $propElementCount
     * @param \Illuminate\Support\Collection $propElements
     * @param string $prop
     * 
     * @return \Illuminate\Support\Collection
     */
    private static function countPropElements($propElementCount, $propElements, $prop)
    {
        foreach ($propElements as $propElement) {
            
            if ($propElementCount->has($propElement->$prop)) {
                $propElementCount[$propElement->$prop] = $propElementCount[$propElement->$prop] + 1;
            }
        }
        return $propElementCount;
    }

    /**
     * @param Academy $academy
     * @param string $prop
     * @param string $propResponseName
     * @param bool $isPropertyRelation
     * 
     * @return array
     */
    public static function getAcademyStatByProp($academy, $prop, $propResponseName, $isPropertyRelation, $candidateFilterData)
    {
        $propElementCount = self::getPropElementCount($academy, $prop);

        $candidates = $academy->candidates()->get();
        if ($candidateFilterData != null) {
            $candidates = self::getFilteredCandidates($candidates, $candidateFilterData);
        }
        foreach ($candidates as $candidate) {
            if ($isPropertyRelation) {
                $propElements = $candidate->$prop()->get();
                $propElementCount = self::countPropElements($propElementCount, $propElements, 'name');
            } else {
                $propElement = $candidate->$prop;
                $propElementCount[$propElement] = $propElementCount[$propElement] + 1;
            }
        }
        $propStat = [];
        foreach ($propElementCount as $propElement => $count) {
            array_push($propStat, [$propResponseName => $propElement, 'count' => $count]);
        }
        $academyStat = ['academy' => $academy, 'statistic' => $propStat];
        return $academyStat;
    }
}
