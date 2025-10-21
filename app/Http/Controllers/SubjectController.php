<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class SubjectController extends SearchableController
{
    const MAX_ITEMS = 5;

    #[\Override]
    function getQuery(): Builder
    {
        return Subject::orderBy('subject_id');
    }



    function list(ServerRequestInterface $request): View
    {
        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria);

        return view('subjects.list', [
            'criteria' => $criteria,
            'subjects' => $query->paginate(self::MAX_ITEMS),
        ]);
    }

    function view(string $subject_id): View
    {
        $subject = $this->find($subject_id);

        return view('subjects.view', [
            'subject' => $subject,
        ]);
    }

    function showCreateForm(): View
    {
        return view('subjects.create-form');
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        $subject = Subject::create($request->getParsedBody());

        return redirect()->route('subjects.list');
    }

    function showUpdateForm(string $subject_id): View
    {
        $subject = $this->find($subject_id);

        return view('products.update-form', [
            'subject' => $subject,
        ]);
    }

    function update(
        ServerRequestInterface $request,
        string $productCode,
    ): RedirectResponse {
        $subject = $this->find($productCode);
        $subject->fill($request->getParsedBody());
        $subject->save();

        return redirect()->route('subjects.view', [
            'subject' => $subject->code,
        ]);
    }

    function delete(string $subject_id): RedirectResponse
    {
        $subject = $this->find($subject_id);
        $subject->delete();

        return redirect()->route('subjects.list');
    }

}