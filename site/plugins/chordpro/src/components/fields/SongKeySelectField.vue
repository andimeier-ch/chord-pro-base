<script>
export default {
  extends: "k-select-field",
  props: {
    songField: { type: String, default: "song" },
    formData:  { type: Object, default: () => ({}) },
  },
  computed: {
    siblingSongUuid() {
      let v = this.formData?.[this.songField];
      if (Array.isArray(v)) v = v[0];
      if (!v) return null;
      const raw = typeof v === "string" ? v : (v.uuid || v.id || null);
      return raw ? String(raw).replace(/^page:\/\//, "") : null;
    },
  },
  watch: {
    siblingSongUuid(newUuid, oldUuid) {
      if (!newUuid || newUuid === oldUuid || this.value) return;
      this.$api.get(`chordpro/songs/${newUuid}/original-key`)
        .then((res) => {
          if (this.value || !res?.key) return;
          this.$emit("input", res.key);
        })
        .catch(() => {});
    },
  },
};
</script>
