<template>
    <x-layout>
        <form @submit.prevent="submit()">
            <h1 class="is-size-1">Create</h1>
            <p v-if="error">{{ error }}</p>
            <input type="text" name="name-string"  class="input my-2" placeholder="name-string" v-model="name-string">

				<input type="number" name="name-integer"  class="input my-2" v-model="name-integer">

				<input type="number" name="name-bigInteger"  class="input my-2" v-model="name-bigInteger">

				<input type="submit" class="button is-primary my-2">

            <a href="/name" class="button is-danger my-2">Cancel</a>
        </form>
    </x-layout>
</template>

<script>
export default {
    name: 'TableCreate',
    status: 'prototype',
    release: '1.0.0',
    data () {
        return {
            error: null,
            name-string: null,
			name-integer: null,
			name-bigInteger: null,
			
        };
    },
    methods: {
        submit () {
            this.$axios.post('/name', {
                name-string: this.name-string,
				name-integer: this.name-integer,
				name-bigInteger: this.name-bigInteger,
				
            })
            .then(response => {
                return window.location = '/name';
            })
            .catch(error => {
                if (error.response) {
                    this.error = error.response.data.message;
                }
            });
        }
    }
};
</script>
