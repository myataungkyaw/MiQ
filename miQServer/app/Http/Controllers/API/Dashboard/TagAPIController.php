<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Requests\API\Dashboard\CreateTagAPIRequest;
use App\Http\Requests\API\Dashboard\UpdateTagAPIRequest;
use App\Models\Dashboard\Tag;
use App\Repositories\Dashboard\TagRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class TagController
 * @package App\Http\Controllers\API\Dashboard
 */

class TagAPIController extends AppBaseController
{
    /** @var  TagRepository */
    private $tagRepository;

    public function __construct(TagRepository $tagRepo)
    {
        $this->tagRepository = $tagRepo;
    }

    /**
     * Display a listing of the Tag.
     * GET|HEAD /tags
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->tagRepository->pushCriteria(new RequestCriteria($request));
        $this->tagRepository->pushCriteria(new LimitOffsetCriteria($request));
        $tags = $this->tagRepository->all();

        return $this->sendResponse($tags->toArray(), 'Tags retrieved successfully');
    }

    /**
     * Store a newly created Tag in storage.
     * POST /tags
     *
     * @param CreateTagAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTagAPIRequest $request)
    {
        $input = $request->all();

        $tag = $this->tagRepository->create($input);

        return $this->sendResponse($tag->toArray(), 'Tag saved successfully');
    }

    /**
     * Display the specified Tag.
     * GET|HEAD /tags/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Tag $tag */
        $tag = $this->tagRepository->findWithoutFail($id);

        if (empty($tag)) {
            return $this->sendError('Tag not found');
        }

        return $this->sendResponse($tag->toArray(), 'Tag retrieved successfully');
    }

    /**
     * Update the specified Tag in storage.
     * PUT/PATCH /tags/{id}
     *
     * @param  int $id
     * @param UpdateTagAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTagAPIRequest $request)
    {
        $input = $request->all();

        /** @var Tag $tag */
        $tag = $this->tagRepository->findWithoutFail($id);

        if (empty($tag)) {
            return $this->sendError('Tag not found');
        }

        $tag = $this->tagRepository->update($input, $id);

        return $this->sendResponse($tag->toArray(), 'Tag updated successfully');
    }

    /**
     * Remove the specified Tag from storage.
     * DELETE /tags/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Tag $tag */
        $tag = $this->tagRepository->findWithoutFail($id);

        if (empty($tag)) {
            return $this->sendError('Tag not found');
        }

        $tag->delete();

        return $this->sendResponse($id, 'Tag deleted successfully');
    }
}
