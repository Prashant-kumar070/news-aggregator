<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Articles",
 *     description="Endpoints related to news articles"
 * )
 *
 */
class ArticleController extends Controller
{
    /**
     * @OA\Get(
     *      path="/articles",
     *      summary="Fetch all articles (paginated)",
     *      tags={"Articles"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="List of articles",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                  @OA\Property(property="id", type="integer"),
     *                  @OA\Property(property="title", type="string"),
     *                  @OA\Property(property="description", type="string"),
     *                  @OA\Property(property="url", type="string"),
     *                  @OA\Property(property="source", type="string"),
     *                  @OA\Property(property="author", type="string"),
     *                  @OA\Property(property="published_at", type="string", format="date-time"),
     *              )),
     *              @OA\Property(property="links", type="object"),
     *              @OA\Property(property="meta", type="object"),
     *          )
     *      )
     * )
     */
    public function index()
    {
        return response()->json(Article::paginate(10));
    }

    /**
     * @OA\Get(
     *      path="/articles/{id}",
     *      summary="Fetch a single article by ID",
     *      tags={"Articles"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the article",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Article details",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="title", type="string"),
     *              @OA\Property(property="description", type="string"),
     *              @OA\Property(property="url", type="string"),
     *              @OA\Property(property="source", type="string"),
     *              @OA\Property(property="author", type="string"),
     *              @OA\Property(property="published_at", type="string", format="date-time"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Article not found"
     *      )
     * )
     */
    public function show($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json($article);
    }

    /**
     * @OA\Post(
     *      path="/articles/search",
     *      summary="Search articles by keyword, date, category, or source",
     *      tags={"Articles"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="keyword",
     *          in="query",
     *          description="Search keyword",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="date",
     *          in="query",
     *          description="Filter by published date (YYYY-MM-DD)",
     *          @OA\Schema(type="string", format="date")
     *      ),
     *      @OA\Parameter(
     *          name="author",
     *          in="query",
     *          description="Filter by author",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="source",
     *          in="query",
     *          description="Filter by news source",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Filtered list of articles",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                  @OA\Property(property="id", type="integer"),
     *                  @OA\Property(property="title", type="string"),
     *                  @OA\Property(property="description", type="string"),
     *                  @OA\Property(property="url", type="string"),
     *                  @OA\Property(property="source", type="string"),
     *                  @OA\Property(property="author", type="string"),
     *                  @OA\Property(property="published_at", type="string", format="date-time"),
     *              )),
     *              @OA\Property(property="links", type="object"),
     *              @OA\Property(property="meta", type="object"),
     *          )
     *      )
     * )
     */
    public function search(Request $request)
    {
        $query = Article::query();
        if ($request->has('keyword')) {
            $query->where('title', 'LIKE', '%' . $request->keyword . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->keyword . '%');
        }

        if ($request->has('date')) {
            $query->whereDate('published_at', $request->date);
        }

        if ($request->has('author')) {
            $query->where('author', $request->category);
        }

        if ($request->has('source')) {
            $query->where('source', $request->source);
        }

        return response()->json($query->paginate(10));
    }
}
