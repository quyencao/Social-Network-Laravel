<template>
    <div>
        <p class="text-center" v-if="loading">
            Loading...
        </p>
        <p class="text-center" v-if="!loading">
            <button class="btn btn-success" v-if="status == 0" @click="add_friend">Add Friend</button>
            <button class="btn btn-success" v-if="status == 'pending'">Accept Friend</button>
            <span class="text-center text-success" v-if="status == 'waiting'">Waiting for response</span>
            <span class="text-center text-success" v-if="status == 'friend'">Friend</span>
        </p>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        props: {
          'profile_user_id': {
              type: Number,
              required: true
          }
        },
        mounted() {
            axios.get('/check_relationship_status/' + this.profile_user_id)
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
                axios.get('/add_friend/' + this.profile_user_id)
                    .then((response) => {
                        if(response.data == 1) {
                            this.status = 'waiting';
                            this.loading = false;
                        }
                    });
            }
        }
    }
</script>
