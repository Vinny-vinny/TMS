<template xmlns="http://www.w3.org/1999/html">
    <div class="row justify-content-center my-4">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">View Task</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Title</label>
                        <p class="form-control-plaintext border rounded p-2">{{ task.title }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <p class="form-control-plaintext border rounded p-2">{{ task.description }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Due Date</label>
                        <p class="form-control-plaintext border rounded p-2">{{ task.due_date }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <p class="form-control-plaintext border rounded p-2">
                            <span :class="task.is_overdue ? 'badge bg-warning' : 'badge bg-success'">
                                {{ task.is_overdue ? 'Overdue' : 'On Time' }}
                            </span>
                        </p>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button @click="goBack" class="btn btn-secondary">
                            Back
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import useTasks from '../../composables/tasks.js';

const { task, getTask } = useTasks();
const route = useRoute();
const router = useRouter();

const goBack = () => {
    router.back();
}

onMounted(() => {
    getTask(route.params.id);
});
</script>
