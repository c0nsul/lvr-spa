<template>
    <div class="container">
        <div class="form-group">
            <h3>{{ name }}</h3>
            <input v-model="name" :class="{ 'is-invalid': $v.name.$error}" class="form-control" type="text"
                   @blur="saveName">
            <div v-if="!$v.name.required" class="invalid-feedback">
                This field is required!
            </div>
            <div v-if="!$v.name.maxLength" class="invalid-feedback">
                MAX Characters: {{ $v.name.$params.maxLength.max }}
            </div>
        </div>

        <form class="mt-3" @submit.prevent="addNewDeskList">
            <div class="form-group">
                <input v-model="desk_list_name" :class="{ 'is-invalid': $v.desk_list_name.$error}" class="form-control"
                       placeholder="Enter desk list name"
                       type="text">
                <div v-if="!$v.desk_list_name.required" class="invalid-feedback">
                    This field is required!
                </div>
                <div v-if="!$v.desk_list_name.maxLength" class="invalid-feedback">
                    MAX Characters: {{ $v.desk_list_name.$params.maxLength.max }}
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Add desk list</button>
        </form>

        <div v-if="error" class="alert alert-danger" role="alert">
            Erorr data loading!
        </div>

        <div class="row">

            <div v-for="deskList in deskLists" class="col-lg-4">
                <div class="card mt-3">
                    <a class="card-body" href="#">
                        <h4 class="card-title">{{ deskList.name }}</h4>
                    </a>
                    <button class="btn btn-danger mt-3" type="button" @click="deleteDeskList(deskList.id)">Remove</button>
                </div>
            </div>
        </div>

        <button v-if="loading" class="btn btn-primary" disabled type="button">
            <span aria-hidden="true" class="spinner-border spinner-border-sm" role="status"></span>
        </button>

    </div>
</template>

<script>
import {maxLength, required} from 'vuelidate/lib/validators';

export default {
    props: [
        'deskId',
    ],
    data() {
        return {
            name: null,
            desk_list_name: null,
            error: false,
            loading: true,
            deskLists: true,
        }
    },
    methods: {
        getDeskLists() {
            this.loading = true;
            axios.get('/api/V1/desk-lists', {
                params: {
                    desk_id: this.deskId
                }
            })
                .then(response => {
                    this.deskLists = response.data.data;
                })
                .finally(() => {
                    this.loading = false;
                })
                .catch(error => {
                    console.log(error);
                    this.error = true;
                })
        },
        saveName() {
            this.$v.$touch()
            if (this.$v.$anyError) {
                return;
            }
            axios.post('/api/V1/desks/' + this.deskId, {
                _method: 'PUT',
                name: this.name,
            })
                .then(response => {
                })
                .finally(() => {
                    this.loading = false;
                })
                .catch(error => {
                    console.log(error);
                    this.error = true;
                })
        },
        addNewDeskList() {
            this.$v.$touch()
            if (this.$v.$anyError) {
                return;
            }
            axios.post('/api/V1/desk-lists', {
                name: this.desk_list_name,
                desk_id: this.deskId
            })
                .then(response => {
                    this.desk_list_name = '';
                    this.loading = true;
                    this.desk_lists = [];
                    this.getDeskLists();
                })
                .finally(() => {
                    this.loading = false;
                })
                .catch(getEerror => {
                    console.log(getEerror.response)
                    /*
                    if (getEerror.response.data.errors.name) {
                        this.errors = [];
                        this.errors.push(getEerror.response.data.errors.name[0])
                    }
                    */
                    this.error = true;
                })
        },
        deleteDeskList(id) {
            if (confirm('Do you really want to delete?')) {
                this.loading = true;
                axios.post('/api/V1/desk-lists/' + id, {
                    _method: 'DELETE',
                })
                    .then(response => {
                        this.desk_lists = [],
                        this.getDeskLists();
                    })
                    .finally(() => {
                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error);
                        this.error = true;
                    })
            }
        }
    },
    mounted() {
        axios.get('/api/V1/desks/' + this.deskId)
            .then(response => {
                this.name = response.data.data.name;
            })
            .finally(() => {
                this.loading = false;
            })
            .catch(error => {
                console.log(error);
                this.error = true;
            });
        this.getDeskLists();
    },
    validations: {
        name: {
            required,
            maxLength: maxLength(255),
        },
        desk_list_name: {
            required,
            maxLength: maxLength(255),
        }
    }
}
</script>
