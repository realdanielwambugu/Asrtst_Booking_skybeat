<?php

Namespace controllers;

use controllers\core\Controller;

use http\Request;


class CategoryController extends Controller
{

    public function index(Request $request)
    {

       $categories = $this->model('category')->all();

       if (isset($request->display))
       {
           return $this->view('partials/CategoryDisplay', $categories);
       }

       return $this->view('partials/CategorySelcet', $categories);
    }

    public function show(Request $request)
    {
         $category = $this->model('Category')->find($request->id);

         return $this->view('services', $category);
    }



}
