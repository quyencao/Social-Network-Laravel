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

            return $pending_friends_requests;
        }

        public function friends_id() {
            return $this->friends()->pluck('id');
        }
    }