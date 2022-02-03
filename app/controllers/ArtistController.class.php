<?php

Namespace controllers;

use controllers\core\Controller;

// use support\proxies\Auth;

// use support\proxies\Gate;

// use support\proxies\Response;

use support\proxies\Middleware;

// use support\proxies\Event;

use support\proxies\Validator;

use support\proxies\Storage;

use support\proxies\Session;

// use events\UserRegisteredEvent;

use http\Request;

class ArtistController extends Controller
{

  protected $valid = false;


  public function middleware()
  {


  }


  public function index(Request $request)
  {
      $artists = $this->model('Artist')->all();

      if ($artists[0]->id)
      {
          if ($request->for != 'admin')
          {
              return $this->view('services', $artists);
          }

          return $this->view('artist', $artists);
      }
  }

  public function validate($request, $constraints = null)
  {
      $validation = Validator::check($request, $constraints);

      if ($validation->fails())
      {
          return $validation->errors()->first();
      }

      return false;
  }

  public function create(Request $request)
  {
      $request->birth_day = formatDate($request->birth_day, "Y-m-d H:i:s");

      if ($error = $this->validate($request->except('photo'), 'createArtist'))
      {
          return $error;
      }

      $artist = $request->only(
                  'name', 'category_id', 'country',
                  'birth_day', 'cost_per_hr', 'biography',
                );

       if ($request->photo['name'])
       {
           $photo = Storage::pushFile($request->photo, 'images.artist');

           $artist['photo'] = $photo->uniqueName;
       }


      $artist = $this->model('Artist')->create($artist);

      $song = $artist->song()->first();

      $firstSong = $song->create([
                     'artist_id' => $artist->id,
                     'title' => $request->song1Title,
                     'album' => $request->song1Album,
                     'releaseYear' => $request->song1RealeseYear,
                   ]);

      $secondSong = $song->create([
                     'artist_id' => $artist->id,
                     'title' => $request->song2Title,
                     'album' => $request->song2Album,
                     'releaseYear' => $request->song2RealeseYear,
                   ]);

      return succes('Artists added succesfully');
  }


  public function show(Request $request)
  {
      if (isset($request->search))
      {
         if ($request->search)
         {
             $artists = $this->model("Artist")
                        ->where('name', ' LIKE ', "%{$request->search}%")->get();

             return $this->view('partials/search', $artists);
         }

      }
      else
      {
          $artist = $this->model("Artist")->find($request->id);

          Session::set('artist_id', $artist->id);
          
          return $this->view('service', $artist);
      }

  }

  public function update()
  {

  }

  public function delete(Request $request)
  {
      $artist = $this->model('Artist')->find($request->id);

      if ($artist->photo != 'default.jpg')
      {
          Storage::delete($artist->photo, 'images.artist');
      }

      $artist->song()->delete();

      $artist->booking()->delete();

      $artist->delete();

      $request->for = 'admin';

      return $this->index($request);
  }

}
