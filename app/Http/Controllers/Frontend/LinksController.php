<?php

namespace App\Http\Controllers\Frontend;

use App\Actions\LinkStoreAction;
use App\Actions\LinkUpdateAction;
use App\DataTransferObjects\LinkStoreDataDTO;
use App\DataTransferObjects\LinkUpdateDataDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use function redirect;

class LinksController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Link::class, 'link');
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Links/Index', [
            'links' => $request
                ->user()
                ->links()
                ->with('author', 'tags')
                ->latest()
                ->paginate(),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Links/Create', [
            'tags' => Tag::query()->take(10)->get(),
            'link' => new Link(),
        ]);
    }

    public function store(StoreLinkRequest $request): RedirectResponse
    {
        LinkStoreAction::execute($request->user(), LinkStoreDataDTO::fromRequest($request));

        return redirect()->route('links.index');
    }

    public function edit(Request $request, Link $link): Response
    {
        return Inertia::render('Links/Edit', [
            'link' => $link->loadMissing('tags'),
            'tags' => Tag::query()->take(10)->get(),
        ]);
    }

    public function update(UpdateLinkRequest $request, Link $link): RedirectResponse
    {
        LinkUpdateAction::execute($link, LinkUpdateDataDTO::fromRequest($request));

        return redirect()->route('links.index');
    }

    public function destroy(Link $link): RedirectResponse
    {
        $link->delete();

        return redirect()->route('links.index');
    }
}
