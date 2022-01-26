<template>
    <div>
        <heading class="mb-6">Nova Two Factor</heading>

        <card
            class="bg-white flex flex-col items-center justify-center px-2"
            style="min-height: 300px"
        >
            <h1 class="text-gray-900 text-4xl text-90 font-light mb-6">
                Recovery Codes
            </h1>

            <p class="text-gray-800 text-lg mb-6">
                Every time you use a recovery code, that one gets removed from your list of recovery codes and a new one is generated to take its place.
            </p>

            <button @click.prevent="openModal" class="btn btn-default btn-primary">View Recovery Codes</button>
        </card>

        <portal to="modals">
            <transition name="fade">
                <recovery-codes-modal
                    v-if="showRecoveryCodesModal"
                    @confirm="confirmModal"
                    @close="closeModal"
                />
            </transition>
        </portal>
    </div>
</template>

<script>
import RecoveryCodesModal from './RecoveryCodesModal.vue'

export default {
    components: {
        RecoveryCodesModal,
    },
    metaInfo() {
        return {
          title: 'Nova Two Factor',
        }
    },
    data() {
        return {
            showRecoveryCodesModal: false,
        }
    },
    mounted() {
        //
    },
    methods: {
        openModal() {
            this.showRecoveryCodesModal = true;
        },
        closeModal() {
            this.showRecoveryCodesModal = false;
        },
        confirmModal() {
            this.closeModal();
        },
    },
}
</script>

<style>
/* Scoped Styles */
</style>
