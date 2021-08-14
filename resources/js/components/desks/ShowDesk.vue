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

            <div v-for="(deskList, index) in deskLists" class="col-lg-4" :key="deskList.id">
                <div class="card mt-3">
                    <div class="card-body">

                        <form v-if="desk_list_input_id == deskList.id"
                              class="d-flex justify-content-between align-items-lg-center"
                              @submit.prevent="updateDeskList(deskList.id,deskList.name)">
                            <input v-model="deskList.name" class="form-control" placeholder="Enter desk list name"
                                   type="text" :class="{'is-invalid': $v.deskLists.$each[index].name.$error}">

                            <div v-if="!$v.deskLists.$each[index].name.required" class="invalid-feedback ml-3">
                                This field is required!
                            </div>
                            <div v-if="!$v.deskLists.$each[index].name.maxLength" class="invalid-feedback">
                                MAX Characters: {{ $v.deskLists.$each[index].name.$params.maxLength.max}}
                            </div>

                            <button aria-label="Close" class="close" type="button" @click="updateDeskList(deskList.id,deskList.name)">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </form>

                        <h4 v-else class="card-title d-flex justify-content-between align-items-lg-center"
                            @click="desk_list_input_id=deskList.id">{{ deskList.name }} <i class="fas fa-pencil-alt"
                                                                                           style="font-size: 15px;cursor: pointer"></i>
                        </h4>
                        <button class="btn btn-danger mt-3" type="button" @click="deleteDeskList(deskList.id)">Remove
                        </button>

                        <div v-for="card in deskList.cards" :key="card.id" class="card mt-3 bg-light">
                            <div class="card-body">
                                <h4 class="card-title d-flex justify-content-between align-items-center mb-3"
                                    style="cursor: pointer;">{{ card.name }}</h4>

                                <button type="button" @click="getCard(card.id)" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
                                    Open
                                </button>

                                <button @click="deleteCard(card.id)" class="btn btn-secondary" type="button">Remove card</button>
                            </div>
                        </div>

                        <form class="mt-3" @submit.prevent="addNewCard(deskList.id)">
                            <input v-model="card_names[deskList.id]" class="form-control" placeholder="enter card name"
                                   type="text" :class="{'is-invalid': $v.card_names.$each[deskList.id].$error}">
                            <div v-if="!$v.card_names.$each[deskList.id].required" class="invalid-feedback">
                                This field is required!
                            </div>
                            <div v-if="!$v.card_names.$each[deskList.id].maxLength" class="invalid-feedback">
                                MAX Characters: {{ $v.card_names.$each[deskList.id].$params.maxLength.max }}
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
        <button v-if="loading" class="btn btn-primary" disabled type="button">
            <span aria-hidden="true" class="spinner-border spinner-border-sm" role="status"></span>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <form @submit.prevent="updateCardName" v-if="show_card_name_input" class="d-flex justify-content-between align-items-lg-center">
                            <input v-model="current_card.name" class="form-control" placeholder="Enter card name" type="text" :class="{'is-invalid': $v.current_card.name.$error}">

                            <div v-if="!$v.current_card.name.required" class="invalid-feedback ml-4">
                                This field is required!
                            </div>
                            <div v-if="!$v.current_card.name.maxLength" class="invalid-feedback">
                                MAX Characters: {{ $v.current_card.name.$params.maxLength.max }}
                            </div>

                            <button aria-label="Close" class="close" type="button" @click="updateCardName">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </form>

                        <h5 class="modal-title" id="exampleModalLabel" v-if="!show_card_name_input" @click="show_card_name_input = true" style="cursor: pointer">Card: {{current_card.name}} <i class="ml-2 fas fa-pencil-alt" style="font-size: 15px;cursor: pointer"></i></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="form-check" v-for="(task,index) in current_card.tasks">
                            <input class="form-check-input" type="checkbox" id="inlineChk" value="opt1">

                            <form @submit.prevent="updateTask(current_card.tasks[index])" v-if="task_input_name_id == task.id" style="width: 50%">
                                <input v-model="current_card.tasks[index].name"  class="form-control" placeholder="Enter task name" type="text" :class="{'is-invalid': $v.name.$error}" style="width: 25%">
                            </form>
                             <label v-else  class="form-check-label" for="inlineChk">{{task.name}}</label>


                            <span  @click="task_input_name_id = task.id" v-if="task_input_name_id != task.id">
                                <i class="ml-3 fas fa-pencil-alt" style="font-size: 15px;cursor: pointer"></i>
                            </span>

                            <button type="button" class="close ml-3"  aria-label="Close" @click="deleteTask(task.id)">
                                <span  aria-hidden="true">&times;</span>
                            </button>
                        </div>


                        <form class="mt-4" @submit.prevent="addNewTask" >
                            <div class="form-group">
                                <input v-model="new_task_name" :class="{ 'is-invalid': $v.new_task_name.$error}" class="form-control" placeholder="Enter task name" type="text">

                                <div v-if="!$v.new_task_name.required" class="invalid-feedback">
                                    This field is required!
                                </div>
                                <div v-if="!$v.new_task_name.maxLength" class="invalid-feedback">
                                    MAX Characters: {{ $v.new_task_name.$params.maxLength.max }}
                                </div>

                            </div>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
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
            deskLists: [],
            desk_list_input_id: null,
            card_names: [],
            current_card: [],
            show_card_name_input: false,
            new_task_name: '',
            task_input_name_id: '',
        }
    },
    methods: {
        updateTask(task){
            this.loading = true;
            axios.post('/api/V1/tasks/' + task.id, {
                _method: "PATCH",
                name: task.name,
                is_done: task.is_done,
                card_id: task.card_id,
            })
                .then(response => {
                    this.task_input_name_id = null;
                })
                .finally(() => {
                    this.loading = false;
                })
                .catch(error => {
                    console.log(error);
                    this.error = true;
                })
        },
        deleteTask(id){
            if (confirm('Do you really want to delete task?')) {
                this.loading = true;
                axios.post('/api/V1/tasks/' + id, {
                    _method: "DELETE",
                })
                    .then(response => {
                        this.$v.$reset();
                        this.getCard(this.current_card.id);
                    })
                    .finally(() => {
                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error);
                        this.error = true;
                    })
            }
        },
        addNewTask(){
            this.$v.new_task_name.$touch()
            if (this.$v.new_task_name.$anyError) {
                return;
            }

            this.loading = true;
            axios.post('/api/V1/tasks', {
                name: this.new_task_name,
                card_id: this.current_card.id,
            })
                .then(response => {
                    this.$v.$reset();
                    this.new_task_name = '';
                    this.getCard(this.current_card.id);

                })
                .finally(() => {
                    this.loading = false;
                })
                .catch(error => {
                    console.log(error.response);
                    this.error = true;
                })
        },
        updateCardName(){
            this.$v.current_card.name.$touch()
            if (this.$v.current_card.name.$anyError) {
                return;
            }
            this.loading = true;
            this.show_card_name_input = false;
            axios.post('/api/V1/cards/' + this.current_card.id, {
                _method: "PATCH",
                name: this.current_card.name,
                desk_list_id: this.current_card.desk_list_id,
            })
                .then(response => {
                    this.show_card_name_input = false;
                    this.$v.$reset();
                    this.getDeskLists();
                })
                .finally(() => {
                    this.loading = false;
                })
                .catch(error => {
                    console.log(error);
                    this.error = true;
                })
        },
        getCard(id){
            this.loading = true;
            axios.get('/api/V1/cards/' + id)
                .then(response => {
                    this.current_card = response.data.data;
                })
                .finally(() => {
                    this.loading = false;
                })
                .catch(error => {
                    console.log(error);
                    this.error = true;
                })
        },
        deleteCard(id){
            if (confirm('Do you really want to delete card?')) {
                this.loading = true;
                axios.post('/api/V1/cards/' + id, {
                    _method: "DELETE",
                })
                    .then(response => {
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
        },
        addNewCard(desk_list_id) {
            this.$v.card_names.$each[desk_list_id].$touch()
            if (this.$v.card_names.$each[desk_list_id].$anyError) {
                return;
            }

            this.loading = true;
            axios.post('/api/V1/cards', {
                name: this.card_names[desk_list_id],
                desk_list_id
            })
                .then(response => {
                    //clear validation fields
                    this.$v.$reset();
                    this.getDeskLists();
                })
                .finally(() => {
                    this.loading = false;
                })
                .catch(error => {
                    console.log(error);
                    this.error = true;
                })
        },
        updateDeskList(id, name) {
            this.$v.deskLists.$touch()
            if (this.$v.deskLists.$anyError) {
                return;
            }
            this.desk_list_input_id=null;
            this.loading = true;
            axios.post('/api/V1/desk-lists/' + id, {
                _method: "PUT",
                name: name,
            })
                .then(response => {
                    this.desk_list_input_id = null;
                })
                .finally(() => {
                    this.loading = false;
                })
                .catch(error => {
                    console.log(error);
                    this.error = true;
                })
        },
        getDeskLists() {
            this.loading = true;
            axios.get('/api/V1/desk-lists', {
                params: {
                    desk_id: this.deskId
                }
            })
                .then(response => {
                    this.deskLists = response.data.data;
                    this.deskLists.forEach(el => {
                        this.card_names[el.id] = ''
                    })
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
            this.$v.name.$touch()
            if (this.$v.name.$anyError) {
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

            this.loading = true;
            axios.post('/api/V1/desk-lists', {
                name: this.desk_list_name,
                desk_id: this.deskId
            })
                .then(response => {
                    this.$v.$reset();
                    this.desk_list_name = '';
                    this.desk_lists = [];
                    this.getDeskLists();
                })
                .finally(() => {
                    this.loading = false;
                })
                .catch(getEerror => {
                    console.log(getEerror.response)
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
        },
        card_names: {
            $each: {
                required,
                maxLength: maxLength(255),
            }
        },
        current_card: {
            name: {
                required,
                maxLength: maxLength(255),
            }
        },
        deskLists: {
            $each: {
                name: {
                    required,
                    maxLength: maxLength(255),
                }
            }
        },
        new_task_name: {
            required,
            maxLength: maxLength(255),
        }
    }
}
</script>
