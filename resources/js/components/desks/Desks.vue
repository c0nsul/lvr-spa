<template>
    <div class="container">
        <h1>Desks</h1>

        <form @submit.prevent="newDesk">
            <div class="form-group">
                <input v-model="name" :class="{ 'is-invalid': $v.name.$error}" class="form-control" placeholder="Enter desk name"
                       type="text">
                <div v-if="!$v.name.required" class="invalid-feedback">
                    This field is required!
                </div>
                <div v-if="!$v.name.maxLength" class="invalid-feedback">
                    MAX Characters: {{ $v.name.$params.maxLength.max }}
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Add</button>
        </form>
        <div v-if="error" class="alert alert-danger mt-3" role="alert">
            Erorr data loading! <br>
            {{errors[0]}}
        </div>
        <div class="row">

            <div v-for="desk in desks" class="col-lg-4">
                <div class="card mt-3">
                    <router-link :to="{name: 'showDesk', params: {deskId: desk.id}}" class="card-body">
                        <h4 class="card-title">{{ desk.name }}</h4>
                    </router-link>
                    <button class="btn btn-danger mt-3" type="button" @click="deleteDesk(desk.id)">Remove</button>
                </div>
            </div>
        </div>


        <button v-if="loading" class="btn btn-primary" disabled type="button">
            <span aria-hidden="true" class="spinner-border spinner-border-sm" role="status"></span>
        </button>


    </div>
</template>

<script>
import {maxLength, required} from "vuelidate/lib/validators";

export default {
    data() {
        return {
            desks: [],
            error: false,
            errors: [],
            loading: true,
            name: null,
        }
    },
    mounted() {
        this.getAllDesks();
    },
    validations: {
        name: {
            required,
            maxLength: maxLength(255),
        }
    },
    methods: {
        newDesk() {
            this.$v.$touch()
            if (this.$v.$anyError) {
                return;
            }
            axios.post('/api/V1/desks', {
                name: this.name,
            })
                .then(response => {
                    this.name = '',
                        this.loading = true,
                        this.desks = [],
                        this.getAllDesks();
                })
                .finally(() => {
                    this.loading = false;
                })
                .catch(getEerror => {
                    if (getEerror.response.data.errors.name){
                        this.errors = [];
                        this.errors.push(getEerror.response.data.errors.name[0])
                    }
                    this.error = true;
                })
        },
        getAllDesks() {
            axios.get('/api/V1/desks')
                .then(response => {
                    this.desks = response.data.data;
                })
                .finally(() => {
                    this.loading = false;
                })
                .catch(error => {
                    console.log(error);
                    this.error = true;
                })
        },
        deleteDesk(id) {
            if (confirm('Do you really want to delete?')) {
                axios.post('/api/V1/desks/' + id, {
                    _method: 'DELETE',
                })
                    .then(response => {
                        this.desks = [],
                            this.getAllDesks();
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
}
</script>
