<template xmlns="http://www.w3.org/1999/html">
    <div class="row justify-content-center my-2">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h5>Update Task</h5>
                </div>
                <div class="card-body">
                    <form @submit.prevent="submitTask">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input v-model="task.title" type="text" class="form-control" id="title"/>
                            <div v-if="validationErrors.title" class="text-danger">
                                {{ validationErrors.title[0] }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea v-model="task.description" class="form-control" id="description"></textarea>
                            <div v-if="validationErrors.description" class="text-danger">
                                {{ validationErrors.description[0] }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Due Date</label> {{task.due_date}}
                            <Datepicker v-model="task.due_date" :format="'YYYY-MM-DD'" class="form-control" id="due_date" required />
                            <div v-if="validationErrors.due_date" class="text-danger">
                                {{ validationErrors.due_date[0] }}
                            </div>
                        </div>
                        <button type="submit" :disabled="isLoading" class="btn btn-primary">
                            {{ isLoading ? 'Updating...' : 'Update Task' }}
                        </button>
                        <button type="button" @click="cancelUpdate" class="btn btn-secondary" style="margin-left: 2%">
                            Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted} from "vue";
import {useRoute, useRouter} from 'vue-router';
import Datepicker from 'vue3-datepicker';
import useTasks from '../../composables/tasks.js'

const { task,getTask, updateTask, validationErrors, isLoading } = useTasks();
const route = useRoute();
const router = useRouter();

const submitTask = () => {
    updateTask(route.params.id, task);
}

const cancelUpdate = () => {
    router.back();
}

onMounted( () => {
     getTask(route.params.id);
});
</script>
