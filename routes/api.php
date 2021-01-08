<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'merchant'], function () {
    Route::group(['namespace' => 'Api'], function () {

        Route::post('login', [
            'as'   => 'merchant/login',
            'uses' => 'MerchantController@login'
        ]);
        Route::post('register', [
            'as'   => 'merchant/register',
            'uses' => 'MerchantController@register'
        ]);
    });

});

Route::group(['middleware' => 'auth:api'], function () {

    Route::group(['namespace' => 'Api'], function () {

        Route::group(['prefix' => 'merchant'], function () {

            Route::post('update/{id}', [
                'as'   => 'merchant/update',
                'uses' => 'MerchantController@update'
            ]);
            Route::delete('destroy/{id}', [
                'as'   => 'merchant/destroy',
                'uses' => 'MerchantController@destroy'
            ]);

            Route::get('logout', [
                'as'   => 'merchant/logout',
                'uses' => 'MerchantController@logout'
            ]);

        });

        Route::group(['prefix' => 'post'], function () {

            Route::get('test', [
                'as'   => 'post/test',
                'uses' => 'PostsController@popularPost'
            ]);


            Route::get('get_all', [
                'as'   => 'post/get_all',
                'uses' => 'PostsController@all'
            ]);

            Route::post('create', [
                'as'   => 'post/create',
                'uses' => 'PostsController@create'
            ]);

            Route::get('get/{id}', [
                'as'   => 'post/get',
                'uses' => 'PostsController@get'
            ]);

            Route::post('update/{id}', [
                'as'   => 'post/update',
                'uses' => 'PostsController@update'
            ]);

            Route::delete('destroy/{id}', [
                'as'   => 'post/destroy',
                'uses' => 'PostsController@destroy'
            ]);

            Route::get('search/{keyword}', [
                'as'   => 'post/search',
                'uses' => 'PostsController@search'
            ]);

            Route::post('/{id}/updatePermission', [
                'as'   => 'post/{id}/updatePermission',
                'uses' => 'PostsController@updatePermission'
            ]);

            Route::post('/{id}/reportPost', [
                'as'   => 'post/{id}/reportPost',
                'uses' => 'PostsController@reportPost'
            ]);


            Route::get('popular', [
                'as'   => 'post/popular',
                'uses' => 'PostsController@popularPost'
            ]);

            Route::get('/{id}/rating', [
                'as'   => 'post/{id}/rating',
                'uses' => 'RatingController@all'
            ]);

            Route::post('/{id}/rating', [
                'as'   => 'post/{id}/rating',
                'uses' => 'RatingController@create'
            ]);

            Route::get('/{id}/comments', [
                'as'   => 'post/{id}/comments',
                'uses' => 'CommentsController@getByPostId'
            ]);

            Route::post('/{id}/comment/new', [
                'as'   => 'post/{id}/comment/new',
                'uses' => 'CommentsController@createCommentByPost'
            ]);

            Route::put('/{id}/comment/{comment_id}', [
                'as'   => 'post/{id}/comment/{comment_id}',
                'uses' => 'CommentsController@updateByPost'
            ]);

            Route::delete('/{id}/comment/{comment_id}', [
                'as'   => 'post/{id}/comment/{comment_id}',
                'uses' => 'CommentsController@destroyByPost'
            ]);

        });

        Route::group(['prefix' => 'comment'], function () {
            Route::get('/{id}', [
                'as'   => 'comment/{id}',
                'uses' => 'CommentsController@get'
            ]);

            Route::post('/create', [
                'as'   => 'comment/create',
                'uses' => 'CommentsController@create'
            ]);

            Route::put('/{id}', [
                'as'   => 'comment/{id}',
                'uses' => 'CommentsController@update'
            ]);

            Route::delete('/{id}', [
                'as'   => 'comment/{id}',
                'uses' => 'CommentsController@destroy'
            ]);
        });

        Route::group(['prefix' => 'complaints'], function () {
            Route::get('/', [
                'as'   => 'complaints',
                'uses' => 'ComplaintController@all'
            ]);
            Route::get('/{report_id}', [
                'as'   => 'complaints/{report_id}',
                'uses' => 'ComplaintController@get'
            ]);
            Route::delete('/{report_id}', [
                'as'   => 'complaints/{report_id}',
                'uses' => 'ComplaintController@destroy'
            ]);
            Route::post('/', [
                'as'   => 'complaints',
                'uses' => 'ComplaintController@create'
            ]);
            Route::post('resolve', [
                'as'   => 'complaints/resolve',
                'uses' => 'ComplaintController@resolve'
            ]);
        });

        /***** Comment Complaint (Reported Comment) *********/
        Route::group(['prefix' => 'report_comment'], function () {

            Route::get('complaints', [
                'as'   => 'report_comment/complaints',
                'uses' => 'ReportedCommentController@getByCommentId'
            ]);
            Route::get('complaint/{id}', [
                'as'   => 'report_comment/complaint',
                'uses' => 'ReportedCommentController@get'
            ]);
            Route::post('report', [
                'as'   => 'report_comment/report',
                'uses' => 'ReportedCommentController@create'
            ]);
            Route::put('report/{id}', [
                'as'   => 'report_comment/report',
                'uses' => 'ReportedCommentController@update'
            ]);
            Route::delete('report/{id}', [
                'as'   => 'report_comment/report',
                'uses' => 'ReportedCommentController@destroy'
            ]);

        });

        Route::group(['prefix' => 'rating'], function () {

            Route::get('/{id}', [
                'as'   => 'rating/{id}',
                'uses' => 'RatingController@get'
            ]);

            Route::put('/{id}', [
                'as'   => 'rating/{id}',
                'uses' => 'RatingController@update'
            ]);

            Route::delete('/{id}', [
                'as'   => 'rating/{id}',
                'uses' => 'RatingController@destroy'
            ]);

        });

        /******* LIKE ********/
        Route::post('{post_id}/like', [
            'as'   => 'api/{post_id}/like',
            'uses' => 'LikeController@create'
        ]);
        //Get all likes for a post:
        Route::get('{post_id}/likes', [
            'as'   => 'api/{post_id}/likes',
            'uses' => 'LikeController@getLikes'
        ]);
        //Get specific like for a specific post:
        Route::get('{post_id}/likes/{like_id}', [
            'as'   => 'api/{post_id}/likes/{like_id}',
            'uses' => 'LikeController@getLike'
        ]);

        //Get specific like
        Route::get('like/{like_id}', [
            'as'   => 'api/like/{like_id}',
            'uses' => 'LikeController@getLikeOnly'
        ]);

        //Delete Like
        Route::delete('like/{like_id}/destroy', [
            'as'   => 'api/like/{like_id}/destroy',
            'uses' => 'LikeController@destroy'
        ]);

        /******* BOOKMARKS ********/
        Route::post('{post_id}/bookmark', [
            'as'   => 'api/{post_id}/bookmark',
            'uses' => 'BookmarkController@create'
        ]);
        //Get all bookmarks for a post:
        Route::get('{post_id}/bookmarks', [
            'as'   => 'api/{post_id}/bookmarks',
            'uses' => 'BookmarkController@getBookmarks'
        ]);
        //Get specific bookmark for a specific post:
        Route::get('{post_id}/bookmarks/{bookmark_id}', [
            'as'   => 'api/{post_id}/bookmarks/{bookmark_id}',
            'uses' => 'BookmarkController@getBookmark'
        ]);

        //Get specific Bookmark
        Route::get('bookmark/{bookmark_id}', [
            'as'   => 'api/bookmark/{bookmark}',
            'uses' => 'BookmarkController@getBookmarkOnly'
        ]);

        //Delete Bookmark
        Route::delete('bookmark/{bookmark_id}/destroy', [
            'as'   => 'api/bookmark/{like_id}/destroy',
            'uses' => 'BookmarkController@destroy'
        ]);

        //Get User Bookmarks
        Route::get('user/{id}/bookmarks', [
            'as'   => 'api/user/{id}/bookmarks',
            'uses' => 'BookmarkController@getByUser'
        ]);

        ////////////////////////////////////////////////

        Route::group(['prefix' => 'category'], function () {

            Route::get('get_all', [
                'as'   => 'category/get_all',
                'uses' => 'CategoriesController@all'
            ]);

            Route::post('create', [
                'as'   => 'category/create',
                'uses' => 'CategoriesController@create'
            ]);

            Route::get('get/{id}', [
                'as'   => 'category/get',
                'uses' => 'CategoriesController@get'
            ]);

            Route::post('update/{id}', [
                'as'   => 'category/update',
                'uses' => 'CategoriesController@update'
            ]);

            Route::delete('destroy/{id}', [
                'as'   => 'category/destroy',
                'uses' => 'CategoriesController@destroy'
            ]);

            Route::get('search/{keyword}', [
                'as'   => 'category/search',
                'uses' => 'CategoriesController@search'
            ]);
        });

        Route::group(['prefix' => 'group'], function () {

            Route::get('get_all', [
                'as'   => 'group/get_all',
                'uses' => 'GroupsController@all'
            ]);

            Route::post('create', [
                'as'   => 'group/create',
                'uses' => 'GroupsController@create'
            ]);

            Route::get('get/{id}', [
                'as'   => 'group/get',
                'uses' => 'GroupsController@get'
            ]);

            Route::post('update/{id}', [
                'as'   => 'group/update',
                'uses' => 'GroupsController@update'
            ]);

            Route::delete('destroy/{id}', [
                'as'   => 'group/destroy',
                'uses' => 'GroupsController@destroy'
            ]);

            Route::get('search/{keyword}', [
                'as'   => 'group/search',
                'uses' => 'GroupsController@search'
            ]);
        });

    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
