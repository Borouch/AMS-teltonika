<?php

namespace App\Services;

use App\Models\Academy;
use App\Models\Candidate;
use App\Utilities\ValidationUtilities;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use App\Models\EducationInstitution;
use Illuminate\Validation\ValidationException;

class AcademyStatisticService
{
    //  Access name is the name with which a property is accessed through candidate model.
    //  For a non-relational property response name is the same as access name.
    private const PROP_ACCESS_NAMES =
        ['positions', 'educationInstitution', 'gender', 'course', 'status', 'application_date'];

    // Only for relational properties' response name is defined explicitly.
    private const PROP_RESPONSE_NAMES = ['position', 'education_institution'];


    /**
     * @param array|null $filterData
     * @return array
     */
    public static function getIndexStatByPosition(array $filterData = null)
    {
        $prop = self::PROP_ACCESS_NAMES[0];
        $propResponseName = self::PROP_RESPONSE_NAMES[0];
        return self::indexStatByProperty($prop, $propResponseName, candidateFilterData: $filterData);
    }


    /**
     * @param int $academyId
     * @param array|null $filterData
     * @return array[]
     * @throws ValidationException
     */
    public static function getShowStatByPosition($academyId, array $filterData = null)
    {
        $prop = self::PROP_ACCESS_NAMES[0];
        $propResponseName = self::PROP_RESPONSE_NAMES[0];

        return self::showStatByProperty($academyId, $prop, $propResponseName, candidateFilterData: $filterData);
    }


    /**
     * @param array|null $filterData
     * @return array
     */
    public static function getIndexStatByEducationInstitution(array $filterData = null)
    {
        $prop = self::PROP_ACCESS_NAMES[1];
        $propResponseName = self::PROP_RESPONSE_NAMES[1];

        return self::indexStatByProperty($prop, $propResponseName, candidateFilterData: $filterData);
    }


    /**
     * @param  $academyId
     * @param array|null $filterData
     * @return array[]
     * @throws ValidationException
     */
    public static function getShowStatByEducationInstitution($academyId, array $filterData = null)
    {
        $prop = self::PROP_ACCESS_NAMES[1];
        $propResponseName = self::PROP_RESPONSE_NAMES[1];

        return self::showStatByProperty($academyId, $prop, $propResponseName, candidateFilterData: $filterData);
    }


    /**
     * @param array|null $filterData
     * @return array
     */
    public static function getIndexStatByGender(array $filterData = null)
    {
        $prop = self::PROP_ACCESS_NAMES[2];

        return self::indexStatByProperty($prop, isPropertyRelation: false, candidateFilterData: $filterData);
    }


    /**
     * @param  $academyId
     * @param $filterData
     * @return array[]
     * @throws ValidationException
     */
    public static function getShowStatByGender($academyId, $filterData = null)
    {
        $prop = self::PROP_ACCESS_NAMES[2];

        return self::showStatByProperty($academyId, $prop, isPropertyRelation: false, candidateFilterData: $filterData);
    }


    /**
     * @param array|null $filterData
     * @return array
     */
    public static function getIndexStatByCourse(array $filterData = null)
    {
        $prop = self::PROP_ACCESS_NAMES[3];

        return self::indexStatByProperty($prop, isPropertyRelation: false, candidateFilterData: $filterData);
    }


    /**
     * @param  $academyId
     * @param array|null $filterData
     * @return array[]
     * @throws ValidationException
     */
    public static function getShowStatByCourse($academyId, array $filterData = null)
    {
        $prop = self::PROP_ACCESS_NAMES[3];

        return self::showStatByProperty($academyId, $prop, isPropertyRelation: false, candidateFilterData: $filterData);
    }


    /**
     * @param array|null $filterData
     * @return array
     */
    public static function getIndexStatByStatus(array $filterData = null)
    {
        $prop = self::PROP_ACCESS_NAMES[4];

        return self::indexStatByProperty($prop, isPropertyRelation: false, candidateFilterData: $filterData);
    }


    /**
     * @param  $academyId
     * @param array|null $filterData
     * @return array[]
     * @throws ValidationException
     */
    public static function getShowStatByStatus($academyId, array $filterData = null)
    {
        $prop = self::PROP_ACCESS_NAMES[4];

        return self::showStatByProperty($academyId, $prop, isPropertyRelation: false, candidateFilterData: $filterData);
    }

    /**
     * @return array
     */
    public static function getIndexStatByApplicationDate()
    {
        $prop = self::PROP_ACCESS_NAMES[5];

        return self::indexStatByProperty($prop, isPropertyRelation: false);
    }


    /**
     * @param  $academyId
     * @return array[]
     * @throws ValidationException
     */
    public static function getShowStatByApplicationDate($academyId)
    {
        $prop = self::PROP_ACCESS_NAMES[5];

        return self::showStatByProperty($academyId, $prop, isPropertyRelation: false);
    }


    /**
     * @param  $academyId
     * @param int $monthNumber
     * @return array
     * @throws ValidationException
     */
    public static function getShowStatByMonth($academyId, int $monthNumber)
    {
        $filterData = ['name' => 'month', 'month_number' => $monthNumber];
        $courseProp = self::PROP_ACCESS_NAMES[3];
        $genderProp = self::PROP_ACCESS_NAMES[2];
        $statusProp = self::PROP_ACCESS_NAMES[4];
        $positionProp = self::PROP_RESPONSE_NAMES[0];
        $eduProp = self::PROP_RESPONSE_NAMES[1];
        $monthCountProp = 'count';
        $statistics = [];
        $statistics[$monthCountProp] = self::getShowStatByMonthCount($academyId, $filterData);
        $statistics[$courseProp] = self::getShowStatByCourse($academyId, $filterData);
        $statistics[$eduProp] = self::getShowStatByEducationInstitution($academyId, $filterData);
        $statistics[$positionProp] = self::getShowStatByPosition($academyId, $filterData);
        $statistics[$statusProp] = self::getShowStatByStatus($academyId, $filterData);
        $statistics[$genderProp] = self::getShowStatByGender($academyId, $filterData);

        $aggregateStat = self::getAggregatedStatistics($statistics);
        return $aggregateStat;
    }

    /**
     * @param int $monthNumber
     * @return array
     */
    public static function getIndexStatByMonth(int $monthNumber)
    {
        $filterData = ['name' => 'month', 'month_number' => $monthNumber];
        $courseProp = self::PROP_ACCESS_NAMES[3];
        $genderProp = self::PROP_ACCESS_NAMES[2];
        $statusProp = self::PROP_ACCESS_NAMES[4];
        $positionProp = self::PROP_RESPONSE_NAMES[0];
        $eduProp = self::PROP_RESPONSE_NAMES[1];
        $monthCountProp = 'count';
        $statistics = [];

        $statistics[$monthCountProp] = self::getIndexStatByMonthCount($filterData);
        $statistics[$courseProp] = self::getIndexStatByCourse($filterData);
        $statistics[$eduProp] = self::getIndexStatByEducationInstitution($filterData);
        $statistics[$positionProp] = self::getIndexStatByPosition($filterData);
        $statistics[$statusProp] = self::getIndexStatByStatus($filterData);
        $statistics[$genderProp] = self::getIndexStatByGender($filterData);

        $aggregateStat = self::getAggregatedStatistics($statistics);
        return $aggregateStat;
    }

    /**
     * @param array $statistics
     * @return array
     */
    public static function getAggregatedStatistics(array $statistics)
    {
        $statPropNames = array_keys($statistics);
        $aggregateStat = [];
        for ($i = 0; $i < count($statPropNames); $i++) {
            $statPropName = $statPropNames[$i];
            if ($i == 0) {
                $aggregateStat = self::initAggregateStat($statistics[$statPropName], $statPropName);
            }
            $aggregateStat = self::aggregateStat(
                $aggregateStat,
                $statistics[$statPropName],
                $statPropName
            );
        }
        return $aggregateStat;
    }

    /**
     * @param  $academyId
     * @param array $filterData
     *
     * @return array
     * @throws ValidationException
     */
    public static function getShowStatByMonthCount($academyId, array $filterData)
    {
        ValidationUtilities::validateAcademyId($academyId);
        $academy = Academy::find($academyId);
        $filteredCountstat = self::getMonthCountStat($academy, $filterData);
        return [$filteredCountstat];
    }

    /**
     * @param array $filterData
     * @return array
     */
    public static function getIndexStatByMonthCount(array $filterData)
    {
        $stat = [];
        foreach (Academy::all() as $academy) {
            $filteredCountstat = self::getMonthCountStat($academy, $filterData);
            $stat[] = $filteredCountstat;
        }

        return $stat;
    }

    /**
     * @param Academy $academy
     * @param array $filterData
     * @return array
     */
    public static function getMonthCountStat(Academy $academy, array $filterData)
    {
        $candidates = $academy->candidates()->get();
        $candidatesCount = self::getFilteredCandidates($candidates, $filterData)->count();
        $filteredCountstat =
            [
                'academy' => $academy,
                'statistic' => [
                    'month' => $filterData['month_number'],
                    'candidates_count' => $candidatesCount,
                ]
            ];

        return $filteredCountstat;
    }

    /**
     * @param array $academiesStat
     * @param string $statPropName
     *
     * @return array
     */
    private static function initAggregateStat(array $academiesStat, string $statPropName)
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
    private static function aggregateStat(array $aggregatedStat, array $academiesStat, string $statPropName)
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
     * For each academy property value constructs an entry where: key = property value and value = 0
     * @param Academy $academy
     * @param string $prop
     *
     * @return EloquentCollection|array
     */
    public static function getInitialPropElementCount(Academy $academy, string $prop)
    {
        // Positions count
        if ($prop == self::PROP_ACCESS_NAMES[0]) {
            $acPos = $academy->positions()->get();
            $positionsCount = $acPos->mapWithKeys(fn($pos) => [$pos->name => 0]);

            return $positionsCount;
        }
        //Education institutions count
        if ($prop == self::PROP_ACCESS_NAMES[1]) {
            $eduCount = EducationInstitution::all()->mapWithKeys(fn($eduProp) => [$eduProp->name => 0]);

            return $eduCount;
        }
        //Gender count
        if ($prop == self::PROP_ACCESS_NAMES[2]) {
            $genderCount = [];
            foreach (Candidate::GENDERS as $genderProp) {
                $genderCount += [$genderProp => 0];
            }

            return $genderCount;
        }
        //Course count
        if ($prop == self::PROP_ACCESS_NAMES[3]) {
            $courseCount = [];
            foreach (Candidate::COURSES as $courseProp) {
                $courseCount += [$courseProp => 0];
            }

            return $courseCount;
        }
        //Statuses count
        if ($prop == self::PROP_ACCESS_NAMES[4]) {
            $statusCount = [];
            foreach (Candidate::STATUSES as $status) {
                $statusCount += [$status => 0];
            }

            return $statusCount;
        }
        //Application dates count
        if ($prop == self::PROP_ACCESS_NAMES[5]) {
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
     * Filters given candidates based on the supplied filter data
     * @param EloquentCollection $candidates
     * @param array $filterData
     *
     * @return EloquentCollection|null
     */
    public static function getFilteredCandidates(EloquentCollection $candidates, array $filterData)
    {
        $filterName = $filterData['name'];
        $filteredCandidates = null;

        if ('month' == $filterName) {
            $filterMonth = $filterData['month_number'];
            $filteredCandidates = $candidates->filter(function ($candidate) use ($filterMonth) {
                $appDate = $candidate->application_date;
                $month = date('n', strtotime($appDate));
                if ($month != $filterMonth) {
                    return false;
                }
                return true;
            });
        }
        return $filteredCandidates;
    }


    /**
     * @param string $prop
     * @param string|null $propResponseName
     * @param bool $isPropertyRelation
     * @param array|null $candidateFilterData
     * @return array
     */
    public static function indexStatByProperty(
        string $prop,
        string|null $propResponseName = null,
        bool $isPropertyRelation = true,
        array|null $candidateFilterData = null
    ) {
        $academies = Academy::all();
        $academiesStat = [];
        foreach ($academies as $academy) {
            $academyStat = self::getAcademyStatByProp(
                $academy,
                $prop,
                $propResponseName,
                $isPropertyRelation,
                $candidateFilterData
            );
            $academiesStat[] = $academyStat;
        }

        return $academiesStat;
    }

    /**
     *
     * @param  $academyId
     * @param string $prop
     * @param string|null $propResponseName
     * @param bool $isPropertyRelation
     * @param array|null $candidateFilterData
     *
     * @return array
     * @throws ValidationException
     */
    public static function showStatByProperty(
        $academyId,
        string $prop,
        string|null $propResponseName = null,
        bool $isPropertyRelation = true,
        array|null $candidateFilterData = null
    ): array {
        ValidationUtilities::validateAcademyId($academyId);
        $academy = Academy::find($academyId);
        $academyStat = self::getAcademyStatByProp(
            $academy,
            $prop,
            $propResponseName,
            $isPropertyRelation,
            $candidateFilterData
        );

        return [$academyStat];
    }


    /**
     * @param array|EloquentCollection $initialRelationElementsCount
     * @param EloquentCollection $candidateRelationElements
     * @return array|EloquentCollection
     */
    private static function getCandidateRelationElementsCount($initialRelationElementsCount, $candidateRelationElements)
    {
        foreach ($candidateRelationElements as $relationElement) {
            $relationElementName = $relationElement->name;
            if ($initialRelationElementsCount->has($relationElementName)) {
                $initialRelationElementsCount[$relationElementName] += 1;
            }
        }

        return $initialRelationElementsCount;
    }

    /**
     * @param Academy $academy
     * @param string $prop
     * @param string|null $propResponseName
     * @param bool $isPropertyRelation
     * @param array|null $candidateFilterData
     * @return array
     */
    public static function getAcademyStatByProp(
        Academy $academy,
        string $prop,
        string|null $propResponseName,
        bool $isPropertyRelation,
        array|null $candidateFilterData
    ) {
        $initialPropElementsCount = self::getInitialPropElementCount($academy, $prop);
        $candidates = $academy->candidates()->get();
        // Only candidates' property elements will be counted that pass filter
        if ($candidateFilterData) {
            $candidates = self::getFilteredCandidates($candidates, $candidateFilterData);
        }

        if ($candidates->count() == 0) {
            $candidatePropElementsCount = $initialPropElementsCount;
        } else {
            $candidatePropElementsCount = self::getCandidatePropElementsCount(
                $candidates,
                $initialPropElementsCount,
                $isPropertyRelation,
                $prop
            );
        }

        $propStat = [];
        if (null == $propResponseName) {
            $propResponseName = $prop;
        }
        foreach ($candidatePropElementsCount as $propElement => $count) {
            $propStat[] = [$propResponseName => $propElement, 'count' => $count];
        }

        return ['academy' => $academy, 'statistic' => $propStat];
    }


    /**
     * @param EloquentCollection $candidates
     * @param EloquentCollection|array $initialPropElementsCount
     * @param bool $isPropertyRelation
     * @param string $prop
     * @return EloquentCollection|array
     */
    private static function getCandidatePropElementsCount(
        EloquentCollection $candidates,
        Collection|array $initialPropElementsCount,
        bool $isPropertyRelation,
        string $prop
    ) {
        $candidatePropElementsCount = $initialPropElementsCount;
        foreach ($candidates as $candidate) {
            if ($isPropertyRelation) {
                $propElements = $candidate->$prop()->get();
                // Since it's a relation it might have multiple elements that need to be counted
                $candidatePropElementsCount = self::getCandidateRelationElementsCount(
                    $initialPropElementsCount,
                    $propElements
                );
            } else {
                $propElement = $candidate->$prop;
                if (isset($candidatePropElementsCount[$propElement])) {
                    $candidatePropElementsCount[$propElement] += 1;
                }
            }
        }

        return $candidatePropElementsCount;
    }
}
