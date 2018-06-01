<?php 

//you belong in here and you can't leave without permission #namespace
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Post;
use Mail;
use Session;
//#use gives permission to go to another folder
//use example\

	class PagesController extends Controller{

		public function getIndex() {
			#Process variable data or params
			#talk to the model
			#receive data back from the model
			#compile or process data from the model if needed
			#pass that data to the correct view
			#you can use pages/welcome as well but . is more Object Oriented
			#just by having Post you're basically saying select(*) from Post
			$posts = Post::orderBy('created_at', 'desc')->limit(4)->get();
			return view('pages.welcome')->withPosts($posts);
		}

		public function getAbout() {

			$first = "Kevin";
			$last = "Bui";

			$fullname = $first . " " . $last;
			$email = 'kbuicuong@gmail.com';
			$data = [];
			$data['email'] = $email;
			$data['fullname'] = $fullname;
			//return view('pages.about')->withFullname($fullname)->withEmail($email);
			return view('pages.about')->withData($data);
		}

		public function getContact() {
			return view('pages.contact');
		}

		public function postContact(Request $request) {
			$this->validate($request, [
				'email' => 'required|email',
				'message' => 'min:10',
				'subject' => 'min:3'
			]);

			$data = array(
				'email' => $request->email,
				'subject' => $request->subject,
				'bodyMessage' => $request->message //message is a reserved variable
			);

			//or queue if you have a lot of emails
			//Mail::send('view', $data, function());
			Mail::send('emails.contact', $data, function($message) use ($data) {

				$message->from($data['email']);
				$message->to('kbuicuong@gmail.com');
				$message->subject($data['subject']);

			});

			Session::flash('success', 'Your Email was Sent!');

			return redirect('/');
		}


	}

?>