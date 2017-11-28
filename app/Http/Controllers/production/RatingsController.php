<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 28.11.2017
 * Time: 1:40 AM
 */

namespace App\Ratings;

use App\Entity\EntityGateway;
use App\Exception\ApiException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Response\ApiResponse;
use Validator;
use Illuminate\Support\Facades\Auth;


class RatingsController extends Controller
{

    protected $ratingGateway;

    public function __construct(RatingGateway $ratingGateway)
    {
        $this->ratingGateway = $ratingGateway;
//        $this->middleware('auth:api');
    }


    public function store($entityId)
    {

//        $user = Auth::user();
        $this->ratingGateway->rateEntity($entityId, $user->id);


    }

    public function index()
    {

        $rating = $this->ratingGateway->getEntitiesRating();

        dd($rating);

    }

    public function show($id)
    {
        $rating = $this->ratingGateway->getEntityRating($id);

        return $rating;
    }


}
