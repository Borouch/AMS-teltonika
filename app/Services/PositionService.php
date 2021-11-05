<?php
namespace App\Services;

use App\Models\Academy;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\AcademiesPositions;

class PositionService 
{
    public static function storePosition(Request $request)
    {   
        $position = new Position();
        $position->name = $request->input('name');
        if ($request->filled('abbreviation'))
        {
            $position->abbreviation = $request->input('abbreviation');
        }
        $position->save();
        //Reassigned in order to fetch id
        $position = Position::all()->last();
        $academies = $request->input('academies');
        $acIds = array_map(fn($ac)=>Academy::where('name','=',$ac)->first()->id,$academies);
        foreach($acIds as $acId)
        {
            $acPosition =new AcademiesPositions();
            $acPosition->academy_id=$acId;
            $acPosition->position_id=$position->id;
            $acPosition->save();
        }
        //Reassigned in order to display associated academies
        $position = Position::all()->last();
        return response()->json(['message'=>'Position stored successfully','position'=>$position],200);
    }       
}