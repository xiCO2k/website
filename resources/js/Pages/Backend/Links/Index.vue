<template>
  <app-layout :title="$t('app.menu.links')">
    <list-for-empty-state v-if="!hasLinks" />
    <list-with-cards v-else />
  </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/Backend/AppLayout";
import ListForEmptyState from "@/Pages/Backend/Links/Partials/ListForEmptyState";
import ListWithCards from "@/Pages/Backend/Links/Partials/ListWithCards";

export default {
    components: {
        ListForEmptyState,
        ListWithCards,
        AppLayout,
    },
    computed:{
        hasLinks(){
            return this.$page.props?.links?.data.length > 0
        }
    }
}
</script>
