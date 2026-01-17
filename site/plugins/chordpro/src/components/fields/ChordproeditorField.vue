<template>
    <k-field :label="label">
        <k-input
            :value="value"
            @input="onInput"
            name="chordProCode"
            type="textarea"
        />
    </k-field>
</template>

<script>
export default {
    props: {
        label: String,
        value: String,
    },
    methods: {
        onInput(value) {
            this.$emit('input', value);
        },
    },
};
</script>
