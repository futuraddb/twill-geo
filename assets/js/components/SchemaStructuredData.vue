<template>
    <!-- eslint-disable -->
    <a17-inputframe
        :error="error"
        :note="note"
        label=""
        :required="required"
        :locale="locale"
        @localize="updateLocale"
    >
        <div>
            <p class="f--note f--small">The structured data can be edited manually. Always check the final data before publishing since it must comply with the <a href="https://schema.org/docs/schemas.html" target="_blank">schema.org</a> vocabulary.</p>
            <p class="f--note f--small">Depending on the state of the item, you can check the validity in these ways:</p>
            <ul class="ul">
                <li class="f--note f--small">content is saved:
                    <ol class="ol">
                        <li class="f--note f--small">Schema.org validator: click <a :href="getValidationUrl('schema')" target="_blank">here</a></li>
                        <li class="f--note f--small">Google rich result validator: click <a :href="getValidationUrl('google')" target="_blank">here</a></li>
                    </ol>
                </li>
                <li class="f--note f--small">content is not saved:
                    <ol class="ol">
                        <li class="f--note f--small">Schema.org validator: copy data from the above field and visit <a href="https://validator.schema.org/?hl=en-US" target="_blank">https://validator.schema.org</a> then paste it into the <i style="font-style: italic">Code snippet</i> section and run the test</li>
                        <li class="f--note f--small">Google rich result validator: copy data from the above field and visit <a href="https://search.google.com/test/rich-results" target="_blank">https://search.google.com/test/rich-results</a> then paste it into the <i style="font-style: italic">Code</i> section and test the code</li>
                    </ol>
                </li>
            </ul>
        </div>
        <a17-button
            :variant="targetFieldValue ? 'secondary' : 'action'"
            :disabled="loading"
            @click="setStructuredData"
        >
            Use AI to generate structured data for AI
        </a17-button>
    </a17-inputframe>
</template>

<script>
/* eslint-disable */

import InputMixin from '@/mixins/input'
import InputframeMixin from '@/mixins/inputFrame'
import { FORM } from '@/store/mutations'
import LocaleMixin from '@/mixins/locale'
import axios from 'axios'

export default {
    name: 'SchemaStructuredData',
    mixins: [InputMixin, InputframeMixin, LocaleMixin],
    props: {
        targetFieldName: {
            type: String,
            required: true,
        },
        apiKey: {
            type: String,
            required: true,
        },
        llmModel: {
            type: String,
            default: 'gpt-5.2',
        },
    },
    data () {
        return {
            loading: false,
        }
    },
    computed: {
        targetFieldValue () {
            let targetField = this.$store.state.form.fields.find(
                field => field.name === this.targetFieldName
            )
            return this.locale ? targetField.value[this.locale.value] : targetField.value;
        },
        publicUrl () {
            // Find TitleEditor to get the URL
            const stickyNav = this.$root.$children.find(c => c.$options.name === 'A17StickyNav');
            const titleEditor = stickyNav ? stickyNav.$children.find(c => c.$options.name === 'A17TitleEditor') : null;
            return titleEditor ? titleEditor.fullUrl : '';
        },
    },
    methods: {
        async setStructuredData () {
            if (this.targetFieldValue && !confirm('This will overwrite the existing data. Continue?')) {
                return;
            }

            this.loading = true;

            try {
                const response = await axios.post('https://api.openai.com/v1/responses', {
                    model: this.llmModel,
                    input: [
                        {
                            role: 'system',
                            content: `Generate structured data (schema.org) in json+ld format for ${this.publicUrl}. Return only the data without any Markdown code block delimiters.`,
                        },
                    ],
                }, {
                    headers: {
                        'Authorization': `Bearer ${this.apiKey}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                });

                const data = response.data;
                let text = '';

                if (data.output_text) {
                    text = data.output_text;
                } else if (data.output && Array.isArray(data.output)) {
                    const pieces = [];
                    data.output.forEach(item => {
                        if (item.content && Array.isArray(item.content)) {
                            item.content.forEach(c => {
                                if (c.type === 'output_text' && c.text) {
                                    pieces.push(c.text);
                                }
                            });
                        }
                    });
                    text = pieces.join('\n');
                }

                // The object that is saved
                const field = {}
                field.name = this.targetFieldName
                if (this.locale) field.locale = this.locale.value
                field.value = text;

                this.$store.commit(FORM.UPDATE_FORM_FIELD, field);
            } catch (error) {
                console.error('Error generating structured data:', error);
                alert('An error occurred while generating structured data.');
            } finally {
                this.loading = false;
            }
        },
        getValidationUrl (type = 'schema') {
            return type === 'schema' ? 'https://validator.schema.org/#url='+encodeURIComponent(this.publicUrl) : 'https://search.google.com/test/rich-results?url='+encodeURIComponent(this.publicUrl);
        }
    },
}
</script>

<style scoped>
.ul > li {
    list-style-type: disc;
    list-style-position: inside;
    padding-left: 8px;
}
.ol li {
    list-style-type: decimal;
    list-style-position: inside;
    padding-left: 8px;
}
.input.input-wrapper- {
    margin-top: 4px;
}
.button {
    margin-top: 24px;
}
</style>
