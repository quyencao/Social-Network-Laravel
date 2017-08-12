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

            return response()->json($friends);
        }
    }