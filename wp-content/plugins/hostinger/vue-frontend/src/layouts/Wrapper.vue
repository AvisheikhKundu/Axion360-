<script lang="ts" setup>
import PluginSplitNotice from "@/components/PluginSplitNotice.vue";
import { HeaderButton } from "@/types";
import Button from "@/components/Button/Button.vue";

type Props = {
  title?: string;
  headerButton?: HeaderButton;
};

const props = defineProps<Props>();
</script>

<template>
  <div class="wrapper">
    <div class="wrapper__content">
      <PluginSplitNotice class="h-mb-20" />
      <div class="wrapper__header">
        <h1 v-if="props.title" class="text-heading-1">{{ props.title }}</h1>
        <Button
          class="wrapper__button"
          v-if="headerButton"
          @click="headerButton?.onClick"
          :to="headerButton?.href"
          size="small"
          variant="outline"
          :target="headerButton.href ? '_blank' : undefined"
          icon-append="icon-launch"
          >{{ headerButton.text }}</Button
        >
      </div>
      <slot />
    </div>
  </div>
</template>

<style lang="scss" scoped>
.wrapper {
  padding: 48px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: left;
  min-height: calc(100vh - var(--header-height));

  @media (max-width: 768px) {
    padding-right: 10px;
    padding-left: 0px;
  }


  &__header{
    display:flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
  }

  &__button {
    background-color: var(--white);
  }

  &__content {
    max-width: 740px;
    width: 100%;
  }
}
</style>
