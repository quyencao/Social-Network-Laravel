<template>
    <div>
        <p class="text-center" v-if="loading">
            Loading...
        </p>
        <p class="text-center" v-if="!loading">
            <button class="btn btn-success" v-if="status == 0" @click="add_friend">Add Friend</button>
            <button class="btn btn-success" v-if="status == 'pending'" @click="accept_friend">Accept Friend</button>
            <span class="text-center text-success" v-if="status == 'waiting'">Waiting for response</span>
            <span class="text-center text-success" v-if="status == 'friend'">Friend</span>
        </p>
    </div>
</template>

<script>

    export default {
        props: {
          'profile_user_id': {
              type: Number,
              required: true
          }
        },
        mounted() {
            this.axios.get('/check_relationship_status/' + this.profile_user_id)
                .then((response) => {
                    this.status = response.data.status;
                    this.loading = false;
                });
        },
        data() {
            return {
                status: '',
                loading: true
            }
        },
        methods: {
            add_friend() {
                this.loading = true;
                this.axios.get('/add_friend/' + this.profile_user_id)
                    .then((response) => {
                        this.loading = false;
                        if(response.data == 1) {
                            this.status = 'waiting';
                            new Noty({
                                type: 'success',
                                layout: 'topRight',
                                text: 'Friend request sent',
                                timeout: 1000,
                                progressBar: false
                            }).show();
                        }
                    });
            },
            accept_friend() {
                this.loading = true;
                this.axios.get('/accept_friend/' + this.profile_user_id)
                    .then((response) => {
                        this.loading = false;
                        if (response.data == 1) {
                            this.status = 'friend';
                            new Noty({
                                type: 'success',
                                layout: 'topRight',
                                text: 'You are now friends!',
                                timeout: 1000,
                                progressBar: false
                            }).show();
                        }
                    });
            }
        }
    }
</script>
