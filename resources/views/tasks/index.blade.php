<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased">

    <div x-data="taskDashboard()" x-init="init()" class="min-h-screen">

        {{-- Header --}}
        <header class="border-b border-gray-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600 text-sm font-bold text-white">T</div>
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900">Task Dashboard</h1>
                        <p class="text-xs text-gray-500">{{ count($tasks) }} total tasks</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Search --}}
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" x-model="search" placeholder="Search tasks..."
                            class="w-44 rounded-lg border border-gray-300 bg-gray-50 py-2 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:w-56">
                    </div>
                    <button @click="showAddModal = true"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Task
                    </button>
                </div>
            </div>
        </header>

        {{-- Success Toast --}}
        <div x-show="showToast" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
            class="fixed right-4 top-4 z-50 rounded-lg bg-green-50 px-4 py-3 text-sm font-medium text-green-800 shadow-lg ring-1 ring-green-200"
            x-text="toastMessage" x-cloak>
        </div>

        {{-- Add Task Modal --}}
        <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div x-show="showAddModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showAddModal = false"></div>
            <div x-show="showAddModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Create New Task</h2>
                    <button @click="showAddModal = false" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="submitTask">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" x-model="form.title" required
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                :class="{'border-red-400': formErrors.title}">
                            <p x-show="formErrors.title" class="mt-1 text-xs text-red-500" x-text="formErrors.title"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea x-model="form.description" rows="3"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select x-model="form.status"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="showAddModal = false"
                            class="rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">Cancel</button>
                        <button type="submit" x-show="!submitting"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">Create Task</button>
                        <button type="button" x-show="submitting" disabled
                            class="rounded-lg bg-indigo-400 px-4 py-2 text-sm font-semibold text-white">Creating...</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Kanban Board --}}
        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

                {{-- Pending Column --}}
                <div class="rounded-xl bg-gray-100/80 p-4">
                    <div class="mb-3 flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-yellow-100 text-xs font-bold text-yellow-800">{{ filteredTasks('pending').length }}</span>
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Pending</h3>
                    </div>
                    <div class="space-y-3">
                        <template x-for="task in filteredTasks('pending')" :key="task.id">
                            <div class="group rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 hover:shadow-md transition-shadow"
                                x-data="{ open: false }">
                                <div class="flex items-start justify-between gap-2">
                                    <h4 class="text-sm font-medium text-gray-900" x-text="task.title"></h4>
                                    <button @click="deleteTask(task.id)"
                                        class="shrink-0 rounded p-1 text-gray-300 opacity-0 transition-all hover:bg-red-50 hover:text-red-500 group-hover:opacity-100">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                                <p x-show="task.description" x-text="task.description" class="mt-1.5 text-xs text-gray-500 line-clamp-2"></p>
                                <div class="mt-3 flex items-center justify-between">
                                    <select @change="updateStatus(task.id, $event.target.value)" x-model="task.status"
                                        class="rounded-lg border border-gray-200 bg-gray-50 px-2 py-1 text-xs font-medium focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                        <option value="pending">Pending</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                    <span class="text-[10px] text-gray-400" x-text="timeAgo(task.created_at)"></span>
                                </div>
                            </div>
                        </template>
                        <template x-if="filteredTasks('pending').length === 0">
                            <div class="rounded-xl border-2 border-dashed border-gray-200 p-6 text-center">
                                <p class="text-xs text-gray-400">No pending tasks</p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- In Progress Column --}}
                <div class="rounded-xl bg-gray-100/80 p-4">
                    <div class="mb-3 flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-800">{{ filteredTasks('in_progress').length }}</span>
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">In Progress</h3>
                    </div>
                    <div class="space-y-3">
                        <template x-for="task in filteredTasks('in_progress')" :key="task.id">
                            <div class="group rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 hover:shadow-md transition-shadow"
                                x-data="{ open: false }">
                                <div class="flex items-start justify-between gap-2">
                                    <h4 class="text-sm font-medium text-gray-900" x-text="task.title"></h4>
                                    <button @click="deleteTask(task.id)"
                                        class="shrink-0 rounded p-1 text-gray-300 opacity-0 transition-all hover:bg-red-50 hover:text-red-500 group-hover:opacity-100">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                                <p x-show="task.description" x-text="task.description" class="mt-1.5 text-xs text-gray-500 line-clamp-2"></p>
                                <div class="mt-3 flex items-center justify-between">
                                    <select @change="updateStatus(task.id, $event.target.value)" x-model="task.status"
                                        class="rounded-lg border border-gray-200 bg-gray-50 px-2 py-1 text-xs font-medium focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                        <option value="pending">Pending</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                    <span class="text-[10px] text-gray-400" x-text="timeAgo(task.created_at)"></span>
                                </div>
                            </div>
                        </template>
                        <template x-if="filteredTasks('in_progress').length === 0">
                            <div class="rounded-xl border-2 border-dashed border-gray-200 p-6 text-center">
                                <p class="text-xs text-gray-400">No tasks in progress</p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Completed Column --}}
                <div class="rounded-xl bg-gray-100/80 p-4">
                    <div class="mb-3 flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-xs font-bold text-green-800">{{ filteredTasks('completed').length }}</span>
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Completed</h3>
                    </div>
                    <div class="space-y-3">
                        <template x-for="task in filteredTasks('completed')" :key="task.id">
                            <div class="group rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 hover:shadow-md transition-shadow"
                                x-data="{ open: false }">
                                <div class="flex items-start justify-between gap-2">
                                    <h4 class="text-sm font-medium text-gray-900" x-text="task.title"></h4>
                                    <button @click="deleteTask(task.id)"
                                        class="shrink-0 rounded p-1 text-gray-300 opacity-0 transition-all hover:bg-red-50 hover:text-red-500 group-hover:opacity-100">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                                <p x-show="task.description" x-text="task.description" class="mt-1.5 text-xs text-gray-500 line-clamp-2"></p>
                                <div class="mt-3 flex items-center justify-between">
                                    <select @change="updateStatus(task.id, $event.target.value)" x-model="task.status"
                                        class="rounded-lg border border-gray-200 bg-gray-50 px-2 py-1 text-xs font-medium focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                        <option value="pending">Pending</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                    <span class="text-[10px] text-gray-400" x-text="timeAgo(task.created_at)"></span>
                                </div>
                            </div>
                        </template>
                        <template x-if="filteredTasks('completed').length === 0">
                            <div class="rounded-xl border-2 border-dashed border-gray-200 p-6 text-center">
                                <p class="text-xs text-gray-400">No completed tasks</p>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        function taskDashboard() {
            return {
                tasks: @json($tasks),
                search: '',
                showAddModal: false,
                showToast: false,
                toastMessage: '',
                submitting: false,
                form: { title: '', description: '', status: 'pending' },
                formErrors: {},

                init() {
                    this.$watch('showAddModal', (val) => {
                        if (!val) {
                            this.form = { title: '', description: '', status: 'pending' };
                            this.formErrors = {};
                        }
                    });
                },

                filteredTasks(status) {
                    return this.tasks.filter(t => {
                        const matchesStatus = t.status === status;
                        const matchesSearch = !this.search ||
                            t.title.toLowerCase().includes(this.search.toLowerCase());
                        return matchesStatus && matchesSearch;
                    });
                },

                async submitTask() {
                    this.submitting = true;
                    this.formErrors = {};
                    try {
                        const res = await fetch('{{ route('task.store') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: JSON.stringify(this.form)
                        });
                        if (!res.ok) {
                            const err = await res.json();
                            if (err.errors) { this.formErrors = err.errors; return; }
                            throw new Error(err.message || 'Error');
                        }
                        const data = await res.json();
                        this.tasks.push(data.task);
                        this.showAddModal = false;
                        this.toast('Task created successfully!');
                    } catch (e) {
                        this.toast('Failed to create task');
                    } finally {
                        this.submitting = false;
                    }
                },

                async updateStatus(id, status) {
                    try {
                        const res = await fetch('{{ url('/tasks') }}/' + id + '/status', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-HTTP-METHOD-OVERRIDE': 'PATCH', 'Accept': 'application/json' },
                            body: JSON.stringify({ status })
                        });
                        if (!res.ok) throw new Error();
                        const task = this.tasks.find(t => t.id === id);
                        if (task) task.status = status;
                        this.toast('Status updated!');
                    } catch (e) {
                        this.toast('Failed to update status');
                    }
                },

                async deleteTask(id) {
                    if (!confirm('Delete this task?')) return;
                    try {
                        const res = await fetch('{{ url('/tasks') }}/' + id, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-HTTP-METHOD-OVERRIDE': 'DELETE', 'Accept': 'application/json' }
                        });
                        if (!res.ok) throw new Error();
                        this.tasks = this.tasks.filter(t => t.id !== id);
                        this.toast('Task deleted!');
                    } catch (e) {
                        this.toast('Failed to delete task');
                    }
                },

                timeAgo(date) {
                    const now = new Date();
                    const d = new Date(date);
                    const diff = Math.floor((now - d) / 1000);
                    if (diff < 60) return 'just now';
                    if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
                    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
                    return Math.floor(diff / 86400) + 'd ago';
                },

                toast(msg) {
                    this.toastMessage = msg;
                    this.showToast = true;
                    if (this._toastTimer) clearTimeout(this._toastTimer);
                    this._toastTimer = setTimeout(() => { this.showToast = false; }, 3000);
                }
            };
        }
    </script>

</body>
</html>