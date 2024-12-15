<script setup>
import { ref, watch } from 'vue';
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  visible: {
    type: Boolean,
    required: true,
  },
  tradeId: {
    type: Number,
    default: null,
  },
  content: {
    type: String,
    default: '',
  },
  isLoading: {
    type: Boolean,
    default: true,
  },
});

const emits = defineEmits(['close', 'save']);

const quillData = ref(props.content);

watch(
  () => props.content,
  (newValue) => {
    quillData.value = newValue;
  }
);

const closeModal = () => {
  emits('close');
};

const saveModal = () => {
  emits('save', quillData.value, props.tradeId);
  closeModal();
};

</script>

<template>
  <div v-if="visible" class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content border-0 shadow">
        <div class="modal-header">
          <h5 class="modal-title">Trade Note</h5>
          <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="card-body">
            <div v-if="isLoading" class="spinner">
              <div class="loading-spinner"></div>
            </div>
            <div v-else>
              <QuillEditor v-model:content="quillData" theme="snow" contentType="html" toolbar="full" />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" @click="saveModal">Save</button>
          <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

.modal.fade {
  display: block;
}

.spinner {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  min-height: 200px;
}

.loading-spinner {
  border: 5px solid #f3f3f3;
  border-top: 5px solid #0a428a;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

</style>
