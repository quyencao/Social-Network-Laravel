<?php
    namespace App\Traits;

    use App\Friendships;
    use App\User;

    trait Friendable {

        public function add_friend($user_requested_id) {
            $friendShips = Friendships::create([
               'requester' => $this->id,
               'user_requested' => $user_requested_id
            ]);

            if($friendShips) {
                return response()->json($friendShips,200);
            }

            return response()->json('fails', 501);

        }

        public function accept_friend($requester) {

            $friendsShip = Friendships::where('requester', $requester)
                                    ->where('user_requested', $this->id)
                                    ->first();

            if($friendsShip) {
                $friendsShip->update([
                   'status' => 1
                ]);

                return response()->json('ok', 200);
            } else {
                return response()->json('fail', 501);
            }

        }

        public function friends() {
            $f1 = Friendships::select('user_requested as friends')
                ->where('requester', $this->id)
                ->where('status', 1);

            $f2 = Friendships::select('requester as friends')
                    ->where('user_requested', $this->id)
                    ->where('status', 1)
                    ->union($f1)
                    ->get();

            $friends = User::whereIn('id', $f2->pluck('friends'))->get();

            return $friends;
        }

        public function pending_friends_requests() {
            // List id of user request friend this user
            $f1 = Friendships::select('requester as pending_friends')
                        ->where('status', 0)
                        ->where('user_requested', $this->id)
                        ->pluck('pending_friends');

            $pending_friends_requests = User::whereIn('id', $f1)
                                             ->get();

            return $pending_friends_requests->toArray();
        }

        public function friends_ids() {
            return collect($this->friends())->pluck('id');
        }

        public function is_friends_with($user_id) {
            // Or use php in_array($user_id, $this->friends_ids->toArray());
            if(collect($this->friends_ids())->contains($user_id)) {
                return response()->json('true', 200);
            }

            return response()->json('false', 200);
        }

        public function pending_friends_requests_ids() {
            return collect($this->pending_friends_requests())->pluck('id')->toArray();
        }

        public function pending_friends_requests_sent() {
            // User that this user sent request to but not accept yet
            $f1 = Friendships::where('requester', $this->id)
                             ->where('status', 0)
                             ->pluck('user_requested');

            $pending_friends_requests_sent = User::whereIn('id', $f1)->get();

            return $pending_friends_requests_sent;
        }

        public function pending_friends_requests_sent_ids() {
            return collect($this->pending_friends_requests_sent())->pluck('id')->toArray();
        }

        public function has_pending_requests_from($user_id) {
            if(collect($this->pending_friends_requests_ids())->contains($user_id)) {
                return response()->json('true', 200);
            }

            return response()->json('false',200);
        }

        public function has_pending_friend_request_sent_to($user_id) {
            if(collect($this->pending_friends_requests_sent_ids())->contains($user_id)) {
                return 1;
            }

            return 0;
        }

//        public function friends() {
//            $friends = User::whereIn('id', $this->friends_id())->get();
//
//            return $friends;
//        }
//
//        public function friends_ids() {
//            $f1 = Friendships::select('user_requested as friends')
//                ->where('requester', $this->id)
//                ->where('status', 1);
//
//            $f2 = Friendships::select('requester as friends')
//                ->where('user_requested', $this->id)
//                ->where('status', 1)
//                ->union($f1)
//                ->get();
//
//            return $f2->pluck('friends');
//        }
    }