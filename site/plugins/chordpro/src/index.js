import ChordproeditorField from "./components/fields/ChordproeditorField.vue";
import SongKeySelectField from "./components/fields/SongKeySelectField.vue";

window.panel.plugin("andimeier-ch/chordpro", {
  fields: {
    chordproeditor: ChordproeditorField,
    songkeyselect: SongKeySelectField,
  },
});
