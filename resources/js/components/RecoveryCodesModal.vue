<template>
    <modal
        role="dialog"
        @modal-close="handleClose"
    >
        <div class="bg-white max-w-md mx-auto my-10 rounded shadow z-10">
            <div class="p-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">View Recovery Codes</h2>

                <div v-if="codes">
                    <p class="mb-6">Here are your recovery codes, keep them safe:</p>

                    <textarea :disabled="true" rows="8" cols="30" v-text="codesText" ref="codes" class="mb-6 text-white bg-90 border border-gray-100 rounded p-4 text-center"></textarea>

                    <div class="text-center"><button type="button" @click.prevent="hideCodes" class="btn btn-default btn-primary">I'm done. Hide them.</button></div>
                </div>

                <div v-else>
                    <p class="mb-6">To view your recovery codes, we need you to confirm your password.</p>
                    <form @submit.prevent="handleSubmit">
                        <div class="mb-6">
                            <div>
                                <label class="block font-bold mb-2" for="password">Password</label>
                                <input ref="passwordInput" class="w-full form-control form-input form-input-bordered" :class="{'border-danger': Boolean(errors.password)}" id="password" type="password"
                                     v-model="password" :placeholder="__('Enter your password...')" autofocus="">
                                <p class="mt-2 text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
                            </div>
                        </div>
                        <div class="text-center"><button :disabled="confirming" type="submit" class="btn btn-default btn-primary">Confirm & View Codes</button></div>
                    </form>
                </div>
            </div>
        </div>
    </modal>
</template>

<script>
    export default {
        name: "RecoveryCodesModal",
        data() {
            return {
                codes: null,
                confirming: false,
                password: '',
                errors: {},
            }
        },
        methods: {
            handleClose() {
                this.$emit('close')
            },
            handleConfirm() {
                this.$emit('confirm')
            },
            handleSubmit() {
                this.confirming = true
                this.errors = {}

                Nova.request().post('/los/2fa/unlocked-recovery-codes', {
                    password: this.password,
                }).then(({ data }) => {
                    this.codes = data.recovery_codes
                }).catch(({ response }) => {
                    this.errors = response.data.errors
                }).finally(() => {
                    this.confirming = false
                })
            },
            hideCodes() {
                this.codes = null
                this.handleClose()
            },
        },
        computed: {
            codesText() {
                return this.codes.join("\n")
            },
        },
        mounted() {
            this.$refs.passwordInput.focus()
        },
    }
</script>

<style scoped>
</style>
