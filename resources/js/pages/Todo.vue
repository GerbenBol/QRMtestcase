<script setup>
import { ref, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import draggableComponent from 'vuedraggable';

const props = defineProps({
    items: {
        type: Array,
        default: () => []
    }
});

const items = ref([...props.items]);

watch(() => props.items, (val) => (items.value = [...val]));

const form = useForm({ text: '' });

function add() {
    if (!form.text.trim())
        return;

    form.post('todo/create', {
        preserveScroll: true,
        onSuccess: () => form.reset('text')
    });
}

function remove(id) {
    form.delete('todo/delete/' + id, {
        preserveScroll: true
    });
}

function save(item) {
    item.editing = false;

    router.put('todo/update/' + item.id, {
        text: item.text
    }, {
        preserveScroll: true
    });
}

function dragEnd() {
    router.post('todo/reorder', {
        items: items.value
    }, {
        preserveScroll: true,
        preserveState: true
    });
}
</script>

<template>
    <div style="margin-left:50px; background-color:black">
        <h1>Todo list</h1>

        <div class="flex">
            <input v-model="form.text" @keyup.enter="add" placeholder="Add item">
            <button @click="add" style="color:white">Add</button>
        </div>

        <draggableComponent v-model="items" item-key="id" handle=".drag-handle" @end="dragEnd">
            <template #item="{element: item}">
                <div style="display:flex; justify-content:space-between; align-items:center; margin:10px; border:1px solid white; border-radius:10px; padding:5px">
                    <div style="display:flex">
                        <span class="drag-handle select-none" style="width:15px">--</span>
                        <div v-if="!item.editing" @dblclick="item.editing = true">
                            {{ item.text }}
                        </div>
                        <input v-else v-model="item.text" @keyup.enter="save(item)" @blur="save(item)" style="border:1px solid white">
                    </div>

                    <div style="display:flex">
                        <button v-if="!item.editing" @click="item.editing = true">
                            Edit
                        </button>
                        <button v-else @click="save(item)">Save</button>
                        <button @click="remove(item.id)" style="margin-left:10px">Delete</button>
                    </div>
                </div>
            </template>
        </draggableComponent>
    </div>
</template>
