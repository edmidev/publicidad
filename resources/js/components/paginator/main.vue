<template>
    <nav aria-label="paginator">
        <ul class="pagination justify-content-center">
            <li v-for="(link, index) in filteredLinks" :key="index" style="cursor: pointer;"
                class="page-item user-select-none pe-auto" :class="link.active ? 'active' : ''">
                <a class="page-link" :class="(link.url == null) ? 'disabled' : ''" @click="updateUrl(link.url)"
                    v-if="link.label.toLowerCase().includes('anterior')">
                    {{ '<' }} </a>
                        <a class="page-link" :class="(link.url == null) ? 'disabled' : ''" @click="updateUrl(link.url)"
                            v-else-if="link.label.toLowerCase().includes('siguiente')">
                            {{ '>' }}
                        </a>
                        <a class="page-link" :class="(link.url == null) ? 'disabled' : ''" @click="updateUrl(link.url)"
                            v-else>
                            {{ link.label }}
                        </a>
            </li>
        </ul>
    </nav>
</template>

<script setup>

import { defineProps, defineEmits, computed } from 'vue'

const emit = defineEmits(['updateUrl'])
const props = defineProps({
    paginator: {
        type: Object,
        default() {
            return { links: [] }
        }
    },
})

const updateUrl = (url) => {
    emit('updateData', url)
}

const filteredLinks = computed(() => {
    // return props.paginator.links
    if (!props.paginator.links) return []

    const filtered = props.paginator.links.filter(link => {
        const label = link.label.toLowerCase()
        // Validate constant links
        if (label.includes('anterior') || label.includes('siguiente') || label.includes('...')) {
            return true
        }

        // validate specific links
        const page = Number(link.label)
        if (page == 1 ||
            page == props.paginator.last_page ||
            (page <= (props.paginator.current_page + 2) &&
                page >= (props.paginator.current_page - 2))) {
            return true
        }
        return false
    })
    return filtered
})

</script>

<style>
.pagination {
    flex-wrap: wrap;
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: 0.25rem;
}
</style>
