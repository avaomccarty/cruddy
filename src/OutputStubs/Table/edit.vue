<template>
    <x-layout>
        <div class="container">
            <div class="row">
                <table class="table is-striped is-narrow" v-if="items && items.length > 0">
                    <a class="button is-primary m-2" href="/names/create">Create</a>
                    <thead>
                        <tr>
                            <th
                                v-for="(item, key) in items"
                                :key="key"
                                >{{ $index }}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <th
                            v-for="(item, key) in items"
                            :key="key">
                            <td
                                v-for="(val, index) in item"
                                :key="index">
                                {{ val }}
                            </td>
                            <td>
                                <a class="button is-primary" :href="'/names/' + item.id">Show</a>
                            </td>
                            <td>
                                <a class="button is-info" :href="'/names/' + item.id + '/edit'">Edit</a>
                            </td>
                            <td>
                                <form @submit="deleteItem(item.id)">
                                    <input type="submit" class="button is-danger" value="Delete">
                                </form>
                            </td>
                        </th>
                    </tbody>
                </table>
                <div v-else>
                    <p class="is-size-3 is-centered">Your query returned zero results.</p>
                    <a class="button is-primary m-2" href="/names/create">Create</a>
                </div>
            </div>
        </div>
    </x-layout>
</template>

<script>
export default {
    name: 'TableEdit',
    status: 'prototype',
    release: '1.0.0',
    data () {
        return {
            items: [],
            error: null,
            name-string: null,
			name-integer: null,
			name-bigInteger: null,
			
        };
    },
    props: {
        propItems: {
            type: Array,
            default: () => []
        }
    },
    mounted () {
        this.items = this.propItems;
    },
    methods: {
        deleteItem (id) {
            this.$axios.delete('/names/' + id)
            .then(response => {
                this.items = this.items.filter(item => item.id !== id);
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


