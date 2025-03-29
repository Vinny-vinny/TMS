import {ref, inject} from 'vue'
import {useRouter} from 'vue-router'
import {handleErrorResponse} from "@/utils/errorHandler.js";

export default function useTasks() {
    const tasks = ref({})
    const task = ref({
        title: '',
        description: '',
        due_date: '',
    })
    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const getTasks = async (
        page = 1,
        searchQuery = '',
        sortBy = 'id',
        sortOrder = 'asc'
    ) => {
        axios.get('/api/task?page=' + page +
            '&search=' + searchQuery +
            '&sort_by=' + sortBy +
            '&sort_order=' + sortOrder
        )
            .then(response => {
                tasks.value = response.data;
            })
    }
    const getTask = async (id) => {
        axios.get('/api/task/' + id)
            .then(response => {
                task.value = response.data.data;
                task.value.due_date = new Date(task.value.due_date);
            })
    }

    const storeTask = async (task) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        axios.post('/api/task', task.value)
            .then(() => {
                swal({
                    icon: 'success',
                    title: 'task saved successfully'
                }).then(() => {
                    router.push({name: 'tasks.index'})
                });
            })
            .catch(error => {
                if (error.response && error.response.status === 422) {
                    validationErrors.value = error.response.data.data;
                } else {
                    handleErrorResponse(error, validationErrors, swal);
                }
            })
            .finally(() => {
                isLoading.value = false
            })
    }
    const updateTask = async (id, task) => {
        if (isLoading.value) return;

        isLoading.value = true;
        validationErrors.value = {};
           const payload = {
            title: task.value.title,
            description: task.value.description,
            due_date: task.value.due_date
           }
        axios.put('/api/task/' + id, payload)
            .then(() => {
                swal({
                    icon: 'success',
                    title: 'Task updated successfully'
                }).then(() => {
                    router.push({name: 'tasks.index'});
                });
            })
            .catch(error => {
                if (error.response && error.response.status === 422) {
                    validationErrors.value = error.response.data.data;
                } else {
                    handleErrorResponse(error, validationErrors, swal);
                }
            })
            .finally(() => {
                isLoading.value = false;
            });
    };

    const deleteTask = async (id) => {
        axios.delete('/api/task/' + id)
            .then(() => {
                swal({
                    icon: 'success',
                    title: 'Task deleted successfully'
                }).then(() => {
                    getTasks();
                });
            })
            .catch(error => {
                handleErrorResponse(error, validationErrors, swal);
            });
    };

    return {
        tasks,
        task,
        getTasks,
        getTask,
        storeTask,
        updateTask,
        deleteTask,
        validationErrors,
        isLoading
    }
}
