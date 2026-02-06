<template>
    <k-field :label="label" :help="help">
        <div ref="editorContainer" class="chordpro-editor-wrapper"></div>
    </k-field>
</template>

<script>
import { EditorView } from '@codemirror/view';
import { EditorState } from '@codemirror/state';
import {
    autocompletion,
    closeBrackets,
    closeBracketsKeymap,
    completionKeymap,
    completionStatus,
    acceptCompletion,
} from '@codemirror/autocomplete';
import { defaultKeymap, history, historyKeymap } from '@codemirror/commands';
import {
    bracketMatching,
    syntaxHighlighting,
    defaultHighlightStyle,
} from '@codemirror/language';
import { lintKeymap } from '@codemirror/lint';
import { highlightSelectionMatches, searchKeymap } from '@codemirror/search';
import {
    drawSelection,
    highlightActiveLine,
    highlightActiveLineGutter,
    highlightSpecialChars,
    keymap,
    lineNumbers,
} from '@codemirror/view';
import { ChordPro } from '@chordbook/codemirror-lang-chordpro';

export default {
    props: {
        label: String,
        help: String,
        value: String,
    },
    data() {
        return {
            editorView: null,
            isUpdatingFromProp: false,
        };
    },
    mounted() {
        this.initializeEditor();
    },
    beforeDestroy() {
        this.destroyEditor();
    },
    watch: {
        value(newValue) {
            this.updateEditorContent(newValue);
        },
    },
    methods: {
        getEditorExtensions() {
            // Base theme for height control
            const baseTheme = EditorView.baseTheme({
                '&': { minHeight: '100%' },
                '.cm-editor': { minHeight: '100%' },
                '.cm-scroller': { flex: '1' },
            });

            // Event handling extension to dispatch custom change events
            // This replicates the event handling from @chordbook/editor
            const eventExtension = EditorView.updateListener.of(
                (viewUpdate) => {
                    if (viewUpdate.docChanged) {
                        // Dispatch custom change event with document content
                        const event = new CustomEvent('change', {
                            bubbles: true,
                            detail: {
                                doc: viewUpdate.state.doc.toString(),
                                viewUpdate,
                            },
                        });
                        viewUpdate.view.dom.dispatchEvent(event);
                    }
                },
            );

            return [
                baseTheme,
                eventExtension, // CRITICAL: Add event handling for change events
                syntaxHighlighting(defaultHighlightStyle), // Light theme syntax highlighting
                ChordPro(),
                lineNumbers(),
                highlightActiveLineGutter(),
                highlightSpecialChars(),
                history(),
                drawSelection(),
                autocompletion(),
                EditorState.allowMultipleSelections.of(true),
                bracketMatching(),
                closeBrackets(),
                highlightActiveLine(),
                highlightSelectionMatches(),
                keymap.of([
                    ...closeBracketsKeymap,
                    ...defaultKeymap,
                    ...searchKeymap,
                    ...historyKeymap,
                    ...completionKeymap,
                    ...lintKeymap,
                    {
                        key: 'Tab',
                        preventDefault: true,
                        run: (e) => {
                            if (completionStatus(e.state))
                                return acceptCompletion(e);
                            return false;
                        },
                    },
                ]),
            ];
        },

        initializeEditor() {
            // Create editor with custom extensions (light theme)
            this.editorView = new EditorView({
                parent: this.$refs.editorContainer,
                doc: this.value || '',
                extensions: this.getEditorExtensions(),
            });

            // Listen for change events
            this.$refs.editorContainer.addEventListener(
                'change',
                this.handleEditorChange,
            );
        },

        handleEditorChange(event) {
            // Prevent infinite loops when updating from prop changes
            if (this.isUpdatingFromProp) {
                return;
            }

            // Extract the document content from the event detail
            const newDoc = event.detail.doc;

            // Emit input event to update v-model in parent
            this.$emit('input', newDoc);
        },

        updateEditorContent(newValue) {
            // Don't update if editor doesn't exist yet
            if (!this.editorView) return;

            // Get current content
            const currentDoc = this.editorView.state.doc.toString();

            // Only update if content actually changed
            if (currentDoc !== newValue) {
                // Set flag to prevent triggering change event
                this.isUpdatingFromProp = true;

                // Create a transaction to replace all content
                this.editorView.dispatch({
                    changes: {
                        from: 0,
                        to: currentDoc.length,
                        insert: newValue || '',
                    },
                });

                // Reset flag after a tick
                this.$nextTick(() => {
                    this.isUpdatingFromProp = false;
                });
            }
        },

        destroyEditor() {
            if (this.editorView) {
                // Remove event listener
                this.$refs.editorContainer.removeEventListener(
                    'change',
                    this.handleEditorChange,
                );

                // Destroy the editor instance
                this.editorView.destroy();
                this.editorView = null;
            }
        },
    },
};
</script>

<style scoped>
.chordpro-editor-wrapper {
    min-height: 500px;
    border: 1px solid var(--color-border, #ddd);
    border-radius: var(--rounded, 4px);
    overflow: hidden;
    background: var(--color-white, #fff);
}

/* Ensure CodeMirror fills the container */
.chordpro-editor-wrapper ::v-deep .cm-editor {
    height: 100%;
}

/* Match Kirby panel font */
.chordpro-editor-wrapper ::v-deep .cm-content {
    font-family: var(
        --font-mono,
        'SFMono-Regular',
        'Consolas',
        'Liberation Mono',
        'Menlo',
        monospace
    );
    font-size: 14px;
    line-height: 1.6;
}

/* Ensure proper padding */
.chordpro-editor-wrapper ::v-deep .cm-scroller {
    padding: 10px;
}
</style>
