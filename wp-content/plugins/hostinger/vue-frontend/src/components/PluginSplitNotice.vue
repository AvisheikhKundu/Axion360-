<script setup lang="ts">
import Card from "@/components/Card.vue";
import Button from "@/components/Button/Button.vue";
import { translate } from "@/utils/helpers";
import { computed, ref } from "vue";

const showNotice = ref(true);

const dismissNotice = () => {
  showNotice.value = false;

  fetch(ajaxurl, {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      action: "hostinger_dismiss_plugin_split_notice",
      nonce: hostinger_tools_data.hts_close_plugin_split_nonce,
    }),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok " + response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      showNotice.value = false;
    })
    .catch((error) => {
      console.error("Error:", error);
    });
};

const isNoticeShown = computed(() => {
  return (
    showNotice.value && parseInt(hostinger_tools_data.plugin_split_notice) > 0
  );
});
</script>
<template>
  <Card v-if="isNoticeShown">
    <template #header>
      <div class="d-flex justify-content-between w-100">
        <div class="d-flex">
          <h2 class="h-m-0">
            {{ translate("hostinger_tools_split_title") }}
          </h2>
        </div>
      </div>
    </template>
    <p class="text-body-2">
      {{ translate("hostinger_tools_split_body") }}
    </p>
    <Button class="h-mt-20" @click="dismissNotice">
      {{ translate("hostinger_tools_split_got_it") }}
    </Button>
  </Card>
</template>

<style scoped lang="scss"></style>
