<script setup lang="ts">
import { RouterView } from "vue-router";
import Modals from "@/components/Modals/Base/Modals.vue";
import Wrapper from "@/layouts/Wrapper.vue";
import { useRoute } from "vue-router";
import { computed } from "vue";
import { HeaderButton } from "@/types";

const route = useRoute();

const headerTitle = computed(() => {
  return route.meta.title as string;
});

const headerButton = computed(() => {
  return route.meta.headerButton as HeaderButton;
});
</script>

<template>
  <div>
    <div id="overhead-button" />
    <Wrapper :title="headerTitle" :header-button="headerButton">
      <RouterView v-slot="{ Component }">
        <Component :is="Component" />
      </RouterView>
    </Wrapper>
    <Modals />
  </div>
</template>

<style lang="scss" scoped>
:deep(.h-button-v2) {
  &:hover {
    cursor: pointer;
  }
}
#overhead-button {
  position: absolute;
  right: 0;
  padding: 40px;
  z-index: 2;

  @media (max-width: 576px) {
    padding: 16px;
  }
}
</style>
