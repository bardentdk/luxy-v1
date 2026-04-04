<template>
  <CommercialLayout>
    <Head title="Contacts CRM" />

    <div style="display:flex; flex-direction:column; gap:20px;">

      <!-- Header -->
      <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:16px;">
        <div>
          <h1 style="font-size:20px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin:0 0 3px;">Contacts CRM</h1>
          <p style="font-size:13px; color:rgba(13,27,42,0.45); margin:0;">{{ contacts.total }} contact{{ contacts.total > 1 ? 's' : '' }} au total</p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
          <!-- Importer les leads -->
          <button
            v-if="pendingLeads > 0"
            @click="importLeads"
            :disabled="importing"
            style="display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border-radius:12px; border:1.5px solid rgba(201,168,76,0.3); background:rgba(201,168,76,0.08); color:#A07828; font-size:13px; font-weight:700; cursor:pointer; font-family:inherit; transition:all 0.2s;"
            class="import-btn"
          >
            <PhDownloadSimple style="width:15px; height:15px;" />
            Importer {{ pendingLeads }} lead{{ pendingLeads > 1 ? 's' : '' }}
          </button>

          <Link
            :href="route('commercial.contacts.create')"
            style="display:inline-flex; align-items:center; gap:8px; background:#0D1B2A; color:white; font-weight:700; font-size:13px; padding:10px 20px; border-radius:12px; text-decoration:none; transition:all 0.2s;"
            class="new-btn"
          >
            <PhPlus style="width:15px; height:15px;" />
            Nouveau contact
          </Link>
        </div>
      </div>

      <!-- Filtres -->
      <div style="background:white; border-radius:16px; padding:16px 20px; display:flex; align-items:center; gap:12px; box-shadow:0 1px 6px rgba(13,27,42,0.05); border:1px solid rgba(13,27,42,0.07); flex-wrap:wrap;">
        <div style="position:relative; flex:1; min-width:200px;">
          <PhMagnifyingGlass style="width:15px; height:15px; color:rgba(13,27,42,0.3); position:absolute; left:12px; top:50%; transform:translateY(-50%);" />
          <input
            v-model="localSearch"
            type="text"
            placeholder="Rechercher un contact..."
            @input="applyFilters"
            style="width:100%; padding:9px 12px 9px 36px; border:1.5px solid rgba(13,27,42,0.1); border-radius:10px; font-size:14px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; box-sizing:border-box; transition:border-color 0.2s;"
            class="search-input"
          />
        </div>

        <div style="display:flex; gap:8px; flex-wrap:wrap;">
          <button
            v-for="s in statuses"
            :key="s.value"
            @click="setStatus(s.value)"
            style="padding:8px 14px; border-radius:100px; font-size:12px; font-weight:700; cursor:pointer; transition:all 0.2s; border:1.5px solid; font-family:inherit;"
            :style="localStatus === s.value
              ? `background:${s.color}18; color:${s.color}; border-color:${s.color}40;`
              : 'background:white; color:rgba(13,27,42,0.5); border-color:rgba(13,27,42,0.12);'"
          >
            {{ s.label }}
          </button>
        </div>
      </div>

      <!-- Tableau contacts -->
      <div style="background:white; border-radius:18px; border:1.5px solid rgba(13,27,42,0.07); overflow:hidden; box-shadow:0 1px 6px rgba(13,27,42,0.05);">
        <table style="width:100%; border-collapse:collapse;">
          <thead>
            <tr style="background:#FAF7F2; border-bottom:1px solid rgba(13,27,42,0.07);">
              <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:700; color:rgba(13,27,42,0.4); text-transform:uppercase; letter-spacing:0.08em;">Contact</th>
              <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:700; color:rgba(13,27,42,0.4); text-transform:uppercase; letter-spacing:0.08em;">Entreprise</th>
              <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:700; color:rgba(13,27,42,0.4); text-transform:uppercase; letter-spacing:0.08em;">Statut</th>
              <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:700; color:rgba(13,27,42,0.4); text-transform:uppercase; letter-spacing:0.08em;">Source</th>
              <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:700; color:rgba(13,27,42,0.4); text-transform:uppercase; letter-spacing:0.08em;">Deals</th>
              <th style="padding:12px 16px; text-align:left; font-size:11px; font-weight:700; color:rgba(13,27,42,0.4); text-transform:uppercase; letter-spacing:0.08em;">Dernier contact</th>
              <th style="padding:12px 16px; text-align:right; font-size:11px; font-weight:700; color:rgba(13,27,42,0.4); text-transform:uppercase; letter-spacing:0.08em;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="contact in contacts.data"
              :key="contact.id"
              style="border-bottom:1px solid rgba(13,27,42,0.05); transition:all 0.15s;"
              class="table-row"
            >
              <!-- Contact -->
              <td style="padding:14px 20px;">
                <div style="display:flex; align-items:center; gap:12px;">
                  <div style="width:38px; height:38px; border-radius:11px; background:linear-gradient(135deg,#0D1B2A,#1A2D42); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <span style="font-size:13px; font-weight:900; color:#C9A84C;">{{ contact.initials }}</span>
                  </div>
                  <div>
                    <Link :href="route('commercial.contacts.show', contact.id)" style="font-size:13px; font-weight:700; color:#0D1B2A; text-decoration:none; display:block;" class="contact-link">
                      {{ contact.full_name }}
                    </Link>
                    <div style="font-size:11px; color:rgba(13,27,42,0.4);">{{ contact.email }}</div>
                  </div>
                </div>
              </td>

              <!-- Entreprise -->
              <td style="padding:14px 16px;">
                <span style="font-size:13px; color:rgba(13,27,42,0.65);">{{ contact.company || '—' }}</span>
              </td>

              <!-- Statut -->
              <td style="padding:14px 16px;">
                <span
                  style="font-size:11px; font-weight:700; padding:4px 11px; border-radius:100px;"
                  :style="`background:${contact.status_color}18; color:${contact.status_color};`"
                >
                  {{ contact.status_label }}
                </span>
              </td>

              <!-- Source -->
              <td style="padding:14px 16px;">
                <span style="font-size:12px; color:rgba(13,27,42,0.45); display:flex; align-items:center; gap:5px;">
                  <PhGlobe v-if="contact.source === 'contact_form'" style="width:13px; height:13px;" />
                  <PhPencil v-else-if="contact.source === 'manual'" style="width:13px; height:13px;" />
                  <PhUploadSimple v-else style="width:13px; height:13px;" />
                  {{ sourceLabel(contact.source) }}
                </span>
              </td>

              <!-- Deals -->
              <td style="padding:14px 16px;">
                <span style="font-size:13px; font-weight:700; color:#0D1B2A;">{{ contact.deals_count }}</span>
              </td>

              <!-- Dernier contact -->
              <td style="padding:14px 16px;">
                <span style="font-size:12px; color:rgba(13,27,42,0.45);">{{ contact.last_contacted_at || 'Jamais' }}</span>
              </td>

              <!-- Actions -->
              <td style="padding:14px 16px; text-align:right;">
                <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
                  <Link
                    :href="route('commercial.contacts.show', contact.id)"
                    style="width:32px; height:32px; border-radius:8px; border:1.5px solid rgba(13,27,42,0.1); background:white; display:flex; align-items:center; justify-content:center; text-decoration:none; color:rgba(13,27,42,0.5); transition:all 0.15s;"
                    class="icon-btn"
                    title="Voir"
                  >
                    <PhEye style="width:14px; height:14px;" />
                  </Link>
                  <Link
                    :href="route('commercial.contacts.edit', contact.id)"
                    style="width:32px; height:32px; border-radius:8px; border:1.5px solid rgba(13,27,42,0.1); background:white; display:flex; align-items:center; justify-content:center; text-decoration:none; color:rgba(13,27,42,0.5); transition:all 0.15s;"
                    class="icon-btn"
                    title="Modifier"
                  >
                    <PhPencil style="width:14px; height:14px;" />
                  </Link>
                  <Link
                    :href="route('commercial.quotes.create', { contact_id: contact.id })"
                    style="width:32px; height:32px; border-radius:8px; border:1.5px solid rgba(201,168,76,0.25); background:rgba(201,168,76,0.08); display:flex; align-items:center; justify-content:center; text-decoration:none; color:#A07828; transition:all 0.15s;"
                    class="icon-btn-gold"
                    title="Nouveau devis"
                  >
                    <PhFileText style="width:14px; height:14px;" />
                  </Link>
                </div>
              </td>
            </tr>

            <!-- Vide -->
            <tr v-if="contacts.data.length === 0">
              <td colspan="7" style="padding:60px; text-align:center;">
                <PhUsers style="width:40px; height:40px; color:rgba(13,27,42,0.15); margin:0 auto 14px; display:block;" />
                <div style="font-size:15px; font-weight:700; color:rgba(13,27,42,0.4); margin-bottom:6px;">Aucun contact trouvé</div>
                <div style="font-size:13px; color:rgba(13,27,42,0.3);">Modifiez vos filtres ou créez un nouveau contact</div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="contacts.last_page > 1" style="padding:16px 20px; border-top:1px solid rgba(13,27,42,0.06); display:flex; align-items:center; justify-content:space-between;">
          <span style="font-size:13px; color:rgba(13,27,42,0.4);">{{ contacts.from }}–{{ contacts.to }} sur {{ contacts.total }}</span>
          <div style="display:flex; gap:6px;">
            <Link
              v-for="page in contacts.last_page"
              :key="page"
              :href="contacts.links.find(l => l.label == page)?.url ?? '#'"
              style="width:32px; height:32px; border-radius:8px; border:1.5px solid; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; text-decoration:none; transition:all 0.15s;"
              :style="page === contacts.current_page
                ? 'background:#0D1B2A; color:white; border-color:#0D1B2A;'
                : 'background:white; color:rgba(13,27,42,0.5); border-color:rgba(13,27,42,0.12);'"
            >
              {{ page }}
            </Link>
          </div>
        </div>
      </div>
    </div>
  </CommercialLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import CommercialLayout from '@/Layouts/CommercialLayout.vue'
import {
  PhPlus, PhMagnifyingGlass, PhDownloadSimple, PhEye,
  PhPencil, PhFileText, PhUsers, PhGlobe, PhUploadSimple,
} from '@phosphor-icons/vue'

const props = defineProps({
  contacts:     Object,
  filters:      Object,
  pendingLeads: Number,
})

const localSearch = ref(props.filters?.search ?? '')
const localStatus = ref(props.filters?.status ?? '')
const importing   = ref(false)

const statuses = [
  { value: '',         label: 'Tous',     color: '#0D1B2A' },
  { value: 'lead',     label: 'Lead',     color: '#6366F1' },
  { value: 'prospect', label: 'Prospect', color: '#F59E0B' },
  { value: 'client',   label: 'Client',   color: '#22C55E' },
  { value: 'lost',     label: 'Perdu',    color: '#EF4444' },
]

function sourceLabel(source) {
  return { manual: 'Manuel', contact_form: 'Formulaire', import: 'Import' }[source] ?? source
}

function setStatus(val) {
  localStatus.value = val
  applyFilters()
}

function applyFilters() {
  router.get(route('commercial.contacts.index'), {
    search: localSearch.value || undefined,
    status: localStatus.value || undefined,
  }, { preserveState: true, replace: true })
}

function importLeads() {
  importing.value = true
  router.post(route('commercial.contacts.import'), {}, {
    onFinish: () => { importing.value = false },
  })
}
</script>

<style scoped>
.table-row:hover    { background:#FAFAF8 !important; }
.contact-link:hover { color:#C9A84C !important; }
.icon-btn:hover     { background:#FAF7F2 !important; color:#0D1B2A !important; }
.icon-btn-gold:hover{ background:rgba(201,168,76,0.18) !important; }
.import-btn:hover   { background:rgba(201,168,76,0.15) !important; }
.new-btn:hover      { background:#1A2D42 !important; transform:translateY(-1px); }
.search-input:focus { border-color:rgba(201,168,76,0.5) !important; background:white !important; }
</style>