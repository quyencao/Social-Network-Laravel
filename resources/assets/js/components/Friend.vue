<template>
    <div class="container">
        <div class="row">
            component ready
        </div>
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
            console.log('Component mounted.')

            axios.get('/check_relationship_status/' + this.profile_user_id)
                .then((response) => {
                    console.log(response);
                });
        }
    }
</script>
