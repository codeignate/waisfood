<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NavigationController extends Controller
{
	public function index() {
		return view('user.index');
	}

	// NEWLY ADDED ROUTES
	public function registration() {
		return view('admin.registration');
	}

	public function dashboard() {
		return view('admin.dashboard');
	}

	

	public function postRecipeProposal() {
		return view('admin.post-recipe-proposal');
	}
}