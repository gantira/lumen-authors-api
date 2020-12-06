<?php

namespace App\Http\Controllers;

use App\Author;
use App\Http\Resources\Author as ResourcesAuthor;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorController extends Controller
{
    use ApiResponser;

    protected $className = "Authors";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * Return the list of authors
     * return Illuminte/Http/Response
     */
    public function index()
    {
        $authors = Author::paginate(5);

        return $this->successResponse(
            ResourcesAuthor::collection($authors)->response()->getData(true),
            'Get All ' . $this->className,
            Response::HTTP_OK
        );
    }

    /**
     * Create new one author
     * @return Illuminate/Http/Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'gender' => 'required|max:255|in:male,female',
            'country' => 'required|max:255',
        ];

        $this->validate($request, $rules);

        $author = Author::create($request->all());

        return $this->successResponse(
            new ResourcesAuthor($author),
            'Success Insert ' . $this->className,
            Response::HTTP_CREATED
        );
    }

    /**
     * Obtains and show one author
     * @return Illuminate/Http/Response
     */
    public function show($author)
    {
        $author = Author::findOrFail($author);

        return $this->successResponse(
            new ResourcesAuthor($author),
            'Show ' . $this->className
        );
    }

    /**
     * Update an existing author
     * @return Illuminate/Http/Response
     */
    public function update(Request $request, $author)
    {
        $rules = [
            'name' => 'max:255',
            'gender' => 'max:255|in:male,female',
            'country' => 'max:255',
        ];

        $this->validate($request, $rules);

        $author = Author::findOrFail($author);

        $author->fill($request->all());

        if ($author->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $author->save();

        return $this->successResponse(
            new ResourcesAuthor($author),
            'Success Update ' . $this->className,
            Response::HTTP_CREATED
        );
    }

    /**
     * Remove an existing author
     * @return Illuminate/Http/Response
     */
    public function destroy($author)
    {
        $author = Author::findOrFail($author);

        $author->delete();

        return $this->successResponse(
            new ResourcesAuthor($author),
            'Success Delete ' . $this->className,
            Response::HTTP_ACCEPTED
        );
    }
}
