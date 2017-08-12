<?php
    namespace App\Traits;

    use App\Friendships;
    use App\User;

    trait Friendable {
        /** Thêm bạn
         * @param $user_requested_id : id của bạn cần thêm
         * @return \Illuminate\Http\JsonResponse
         */
        public function add_friend($user_requested_id) {
            // Nếu add friend to myself
            if($this->id == $user_requested_id) {
                return 0;
            }

            // Nếu user này và người nhận $user_requested_id đã là bạn
            if($this->is_friends_with($user_requested_id) == 1) {
                return 'Already friends';
            }

            // Nếu add friend đã gửi lời mời trước đó
            if($this->has_pending_friend_request_sent_to($user_requested_id) == 1) {
                return 'You already sent request to this user';
            }

            // Nếu đã có lời mời từ $user_requested_id
            if($this->has_pending_requests_from($user_requested_id) == 1) {
                return $this->accept_friend($user_requested_id);
            }

            $friendShips = Friendships::create([
               'requester' => $this->id,
               'user_requested' => $user_requested_id
            ]);

            if($friendShips) {
                return 1;
            }

            return 0;

        }

        /** Chấp nhận lời kết bạn
         * @param $requester : id của người gửi lời mời kết bạn tới người dùng này
         * @return \Illuminate\Http\JsonResponse
         */
        public function accept_friend($requester) {
            //Nếu user này không có lời mời từ $requester
            if($this->has_pending_requests_from($requester) == 0) {
                return 0;
            }

            $friendsShip = Friendships::where('requester', $requester)
                                    ->where('user_requested', $this->id)
                                    ->first();

            if($friendsShip) {
                $friendsShip->update([
                   'status' => 1
                ]);

                return 1;
            } else {
                return 0;
            }

        }

        /**
         * Lấy lại list bạn của người dùng này
         * @return \Illuminate\Database\Eloquent\Collection|static[]
         */
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

        /**
         * Lấy lại list user đã gửi lời mời kết bạn đến user này nhưng chưa chấp nhận
         * @return array
         */
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

        /**
         * Lấy list ids của những user là bạn của mình
         * @return static
         */
        public function friends_ids() {
            return collect($this->friends())->pluck('id');
        }

        /**
         * Kiểm tra xem mình có là bạn với 1 user nào đó
         * @param $user_id
         * @return int
         */
        public function is_friends_with($user_id) {
            // Or use php in_array($user_id, $this->friends_ids->toArray());
            if(collect($this->friends_ids())->contains($user_id)) {
                return 1;
            }

            return 0;
        }

        /**
         * List id của những user đã gửi lời mời kết bạn đến mình nhưng chưa được chấp nhận
         * @return array
         */
        public function pending_friends_requests_ids() {
            return collect($this->pending_friends_requests())->pluck('id')->toArray();
        }

        /**
         * List user mà mình đã gửi lời mời kết bạn nhưng chưa được chấp nhận
         * @return \Illuminate\Database\Eloquent\Collection|static[]
         */
        public function pending_friends_requests_sent() {
            // User that this user sent request to but not accept yet
            $f1 = Friendships::where('requester', $this->id)
                             ->where('status', 0)
                             ->pluck('user_requested');

            $pending_friends_requests_sent = User::whereIn('id', $f1)->get();

            return $pending_friends_requests_sent;
        }

        /**
         * List id của những user mà mình đã gửi lời mời kết bạn nhưng chưa được chấp nhận
         * @return array
         */
        public function pending_friends_requests_sent_ids() {
            return collect($this->pending_friends_requests_sent())->pluck('id')->toArray();
        }

        /**
         * Kiểm tra xem mình có nhận được lời mời kết bạn từ user_id ko
         * @param $user_id
         * @return int
         */
        public function has_pending_requests_from($user_id) {
            if(collect($this->pending_friends_requests_ids())->contains($user_id)) {
                return 1;
            }

            return 0;
        }

        /**
         * Kiểm tra xem mình có gửi lời mời kết bạn cho user_id ko
         * @param $user_id
         * @return int
         */
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