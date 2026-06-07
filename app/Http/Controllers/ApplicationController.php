<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\DocumentRequirement;

class ApplicationController extends Controller
{

public function publicationFunding()
{
    $categoryId = 1;
    $typeId = 1;

    $forms = Form::where('category_id', $categoryId)
        ->where('type_id', $typeId)
        ->get();

    $documents = DocumentRequirement::where('category_id', $categoryId)
        ->where('type_id', $typeId)
        ->get()
        ->groupBy('section');

    return view('publication_funding', compact(
        'forms',
        'documents',
        'categoryId',
        'typeId'
    ));
}
public function publicationReward()
{
    $categoryId = 1;
    $typeId = 2;

    $forms = Form::where('category_id', $categoryId)
        ->where('type_id', $typeId)
        ->get();

    $documents = DocumentRequirement::where('category_id', $categoryId)
        ->where('type_id', $typeId)
        ->get()
        ->groupBy('section');

    return view('publication_reward', compact(
        'forms',
        'documents',
        'categoryId',
        'typeId'
    ));
}
public function grantGeneral()
{
    $categoryId = 2;
    $typeId = 3;

    $forms = Form::where('category_id', $categoryId)
        ->where('type_id', $typeId)
        ->get();

    $documents = DocumentRequirement::where('category_id', $categoryId)
        ->where('type_id', $typeId)
        ->get()
        ->groupBy('section');

    return view('general', compact(
        'forms',
        'documents',
        'categoryId',
        'typeId'
    ));
}
public function grantPurchase()
{
    $categoryId = 2;
    $typeId = 4;

    $forms = Form::where('category_id', $categoryId)
        ->where('type_id', $typeId)
        ->get();

    $documents = DocumentRequirement::where('category_id', $categoryId)
        ->where('type_id', $typeId)
        ->get()
        ->groupBy('section');

    return view('purchase', compact(
        'forms',
        'documents',
        'categoryId',
        'typeId'
    ));
}
    public function virement()
    {
        $categoryId = 2;
        $typeId = 8;

        $forms = Form::where('category_id', $categoryId)
            ->where('type_id', $typeId)
            ->get();

        $documents = DocumentRequirement::where('category_id', $categoryId)
            ->where('type_id', $typeId)
            ->get()
            ->groupBy('section');

        return view('virement', compact(
            'forms',
            'documents',
            'categoryId',
            'typeId'
        ));
    }
    public function grantGraduate()
{
    $categoryId = 2;
    $typeId = 5;

    $forms = Form::where('category_id', $categoryId)
        ->where('type_id', $typeId)
        ->get();

    $documents = DocumentRequirement::where('category_id', $categoryId)
        ->where('type_id', $typeId)
        ->get()
        ->groupBy('section');

    return view('graduate', compact(
        'forms',
        'documents',
        'categoryId',
        'typeId'
    ));
}
public function conferenceLocal()
{
    $categoryId = 3;
    $typeId = 6;

    $forms = Form::where('category_id', $categoryId)
        ->where('type_id', $typeId)
        ->get();

    $documents = DocumentRequirement::where('category_id', $categoryId)
        ->where('type_id', $typeId)
        ->get()
        ->groupBy('section');

    return view('conference.local', compact(
        'forms',
        'documents',
        'categoryId',
        'typeId'
    ));
}
public function conferenceOverseas()
{
    $categoryId = 3;
    $typeId = 7;

    $forms = Form::where('category_id', $categoryId)
        ->where('type_id', $typeId)
        ->get();

    $documents = DocumentRequirement::where('category_id', $categoryId)
        ->where('type_id', $typeId)
        ->get()
        ->groupBy('section');

    return view('conference.overseas', compact(
        'forms',
        'documents',
        'categoryId',
        'typeId'
    ));
}
}
