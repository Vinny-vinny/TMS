import { ref, reactive, inject } from 'vue'
import { useRouter } from "vue-router";
import { useAuthStore } from "../store/auth";
import {handleErrorResponse} from "@/utils/errorHandler.js";

let user = reactive({
    name: '',
    email: '',
})

export default function useAuth() {
    const authStore = useAuthStore();
    const processing = ref(false)
    const validationErrors = ref({})
    const router = useRouter()
    const swal = inject('$swal')

    const loginForm = reactive({
        email: '',
        password: '',
        remember: false
    })

    const resetForm = reactive({
        email: '',
        token: '',
        password: '',
        password_confirmation: ''
    })

    const submitLogin = async () => {
        if (processing.value) return

        processing.value = true
        validationErrors.value = {}

        try {
             await axios.post('/api/login', loginForm)
            await authStore.getUser();
            await loginUser();
            await router.push({ name: 'home.index' });
        } catch (error) {
            if (error.response) {
                if (error.response.status === 422) {
                    validationErrors.value = error.response.data.data;
                }else if (error.response.status === 401) {
                    validationErrors.value = { general: "Invalid credentials, please double check your email or password." }
                }else {
                    handleErrorResponse(error, validationErrors, swal);
                }
            }
        } finally {
            processing.value = false
        }
    }

    const loginUser = () => {
        user = authStore.user
    }

    const getUser = async () => {
        if (authStore.authenticated) {
            await authStore.getUser()
            await loginUser()
        }
    }

    const logout = async () => {
        if (processing.value) return

        processing.value = true

        try {
            await axios.post('/api/logout')
            user.name = ''
            user.email = ''
            authStore.logout()
            router.push({ name: 'auth.login' })
        } catch (error) {
            // swal({
            //     icon: 'error',
            //     title: error.response.status,
            //     text: error.response.statusText
            // })
        } finally {
            processing.value = false
        }
    }

    return {
        loginForm,
        resetForm,
        validationErrors,
        processing,
        submitLogin,
        user,
        getUser,
        logout
    }
}
