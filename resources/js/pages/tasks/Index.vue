<template>
    <div class="row justify-content-center my-2">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tasks</h5>
                    <router-link :to="{ name: 'tasks.create' }" class="btn btn-primary btn-sm">
                        Create Task
                    </router-link>
                </div>
                <div class="card-body shadow-sm">
                    <div class="mb-4">
                        <input v-model="searchQuery" type="text" placeholder="Search by title or due date..." class="form-control w-100 w-md-25" @input="debouncedSearch" />
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="text-start" @click="sort('id')">
                                    #
                                    <i :class="getSortIcon('id')"></i>
                                </th>
                                <th class="text-left" @click="sort('title')">
                                    Title
                                    <i :class="getSortIcon('title')"></i>
                                </th>
                                <th class="text-left" @click="sort('due_date')">
                                    Due Date
                                    <i :class="getSortIcon('due_date')"></i>
                                </th>
                                <th class="text-left" @click="sort('date_created')">
                                    Date Created
                                    <i :class="getSortIcon('date_created')"></i>
                                </th>
                                <th class="text-left">Status</th>
                                <th class="text-left">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="task in tasks.data" :key="task.id">
                                <td class="text-sm">{{ task.id }}</td>
                                <td class="text-sm">{{ task.title }}</td>
                                <td class="text-sm">
                                    {{ task.date_created }}
                                </td>
                                <td class="text-sm">{{ task.date_created }}</td>
                                <td class="text-sm">
                                   <span :class="task.is_overdue ? 'badge bg-warning' : 'badge bg-success'">
                                        {{ task.is_overdue ? 'Overdue' : 'On Time' }}
                                    </span>
                                </td>
                                <td class="text-sm">
                                    <router-link :to="{ name: 'tasks.view', params: { id: task.id } }" class="btn btn-sm btn-info me-2 mb-2">View</router-link>
                                    <router-link :to="{ name: 'tasks.edit', params: { id: task.id } }" class="btn btn-sm btn-warning me-2 mb-2">Edit</router-link>
                                    <button @click="deleteTask(task.id)" class="btn btn-sm btn-danger mb-2">Delete</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <Pagination :data="tasks" :limit="3" @pagination-change-page="handlePageChange" class="mt-4" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { debounce } from 'lodash';
import useTasks from '../../composables/tasks.js';
import { sortTasks, getSortIcon as getSortIconUtil } from '../../utils/sorting.js';

const searchQuery = ref('');
const sortBy = ref('id');
const sortOrder = ref('asc');

const { tasks, getTasks, deleteTask } = useTasks();

const debouncedSearch = debounce(() => {
    getTasks(1, searchQuery.value, sortBy.value, sortOrder.value);
}, 300);

const handlePageChange = (page) => {
    getTasks(page, searchQuery.value, sortBy.value, sortOrder.value);
};

const sort = (field) => {
    sortTasks(sortBy, sortOrder, field);
    getTasks(1, searchQuery.value, sortBy.value, sortOrder.value);
};

const getSortIcon = (field) => {
    return getSortIconUtil(sortBy.value, sortOrder.value, field);
};

onMounted(() => {
    getTasks();
});
</script>
