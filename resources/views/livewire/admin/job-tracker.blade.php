<div x-data="{ showDeleteModal: @entangle('showDeleteModal'), deleteId: @entangle('deleteId') }">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="terminal-text font-mono text-sm mb-1">
                <span class="text-slate-500">$</span> ./job-tracker.sh
            </div>
            <p class="text-slate-400">Track your job applications and interview status</p>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Search -->
            <div class="relative hidden md:block">
                <input 
                    wire:model.live.debounce.300ms="search"
                    type="text" 
                    placeholder="Search companies..." 
                    class="bg-slate-900 border border-slate-700 text-slate-300 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-64 pl-10 p-2.5"
                >
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-slate-500"></i>
                </div>
            </div>

            <!-- Filter Status -->
            <select 
                wire:model.live="filterStatus"
                class="bg-slate-900 border border-slate-700 text-slate-300 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 p-2.5"
            >
                <option value="">All Statuses</option>
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>

            <button 
                wire:click="openCreateModal"
                class="flex items-center space-x-2 px-5 py-2.5 bg-gradient-to-r from-cyan-500/20 to-blue-500/20 border border-cyan-500/50 rounded-xl text-cyan-400 hover:from-cyan-500/30 hover:to-blue-500/30 hover:border-cyan-400 transition-all duration-300 shadow-lg shadow-cyan-500/10"
            >
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span class="font-medium">New Application</span>
            </button>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-gradient-to-r from-green-500/20 to-emerald-500/20 border border-green-500/50 rounded-xl text-green-400 flex items-center space-x-3 animate-pulse">
            <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($jobs as $job)
            <div class="glass-card p-5 group hover:border-cyan-500/30 transition-all duration-300 relative overflow-hidden">
                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    @php
                        $statusColors = [
                            'applied' => 'text-slate-400 bg-slate-500/10 border-slate-500/20',
                            'interview' => 'text-amber-400 bg-amber-500/10 border-amber-500/20',
                            'offer' => 'text-green-400 bg-green-500/10 border-green-500/20',
                            'rejected' => 'text-red-400 bg-red-500/10 border-red-500/20',
                        ];
                        $colorClass = $statusColors[$job->status] ?? 'text-slate-400 bg-slate-500/10 border-slate-500/20';
                    @endphp
                    <span class="px-2.5 py-1 rounded-md text-xs font-medium border {{ $colorClass }}">
                        {{ ucfirst($job->status) }}
                    </span>
                </div>

                <div class="flex items-start justify-between mb-4 mt-1">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700 flex items-center justify-center text-slate-300 font-bold text-lg">
                        {{ substr($job->company, 0, 1) }}
                    </div>
                </div>

                <h3 class="text-white font-semibold text-lg truncate">{{ $job->position }}</h3>
                <p class="text-slate-400 text-sm mb-4">{{ $job->company }}</p>

                <div class="space-y-2 text-sm text-slate-400 mb-6">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-500"></i>
                        <span>{{ $job->applied_date ? $job->applied_date->format('d M Y') : 'N/A' }}</span>
                    </div>
                    @if($job->salary)
                    <div class="flex items-center space-x-2">
                        <i data-lucide="dollar-sign" class="w-4 h-4 text-slate-500"></i>
                        <span>{{ $job->salary }}</span>
                    </div>
                    @endif
                    @if($job->link)
                    <a href="{{ $job->link }}" target="_blank" class="flex items-center space-x-2 text-cyan-400 hover:text-cyan-300 hover:underline transition-colors w-fit">
                        <i data-lucide="external-link" class="w-4 h-4"></i>
                        <span>Job Link</span>
                    </a>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-white/5">
                    <button 
                        wire:click="openEditModal({{ $job->id }})"
                        class="text-xs font-medium text-slate-400 hover:text-white transition-colors"
                    >
                        Edit Details
                    </button>
                    <button 
                        @click="deleteId = {{ $job->id }}; showDeleteModal = true"
                        class="text-xs font-medium text-red-400/70 hover:text-red-400 transition-colors"
                    >
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-slate-500">
                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-slate-800/50 flex items-center justify-center">
                    <i data-lucide="briefcase" class="w-10 h-10 opacity-50"></i>
                </div>
                <p class="text-lg font-medium mb-1">No job applications found</p>
                <p class="text-sm">Time to start applying!</p>
            </div>
        @endforelse
    </div>
    
    <div class="mt-6">
        {{ $jobs->links() }}
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div 
            class="fixed inset-0 z-[60] flex items-start justify-center p-4 pt-24"
            x-data="{ show: false }"
            x-init="setTimeout(() => show = true, 10)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
        >
            <!-- Backdrop -->
            <div 
                class="fixed inset-0 bg-black/50 backdrop-blur-md"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                wire:click="closeModal"
            ></div>
            
            <!-- Modal Content -->
            <div 
                class="relative w-full max-w-2xl"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            >
                <div class="bg-slate-900 border border-slate-700 shadow-2xl shadow-cyan-500/10 overflow-hidden rounded-xl">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 border-b border-slate-700">
                        <div class="flex items-center justify-between">
                            <h3 class="font-medium text-white">
                                {{ $isEditing ? 'Edit Application' : 'New Application' }}
                            </h3>
                            <button 
                                wire:click="closeModal" 
                                class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-700 transition-all duration-200"
                            >
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6">
                        <form wire:submit="save" class="space-y-4">
                            <!-- Company & Position -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300">Company</label>
                                    <input wire:model="company" type="text" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-cyan-500 transition-all">
                                    @error('company') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300">Position</label>
                                    <input wire:model="position" type="text" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-cyan-500 transition-all">
                                    @error('position') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Status & Date -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300">Status</label>
                                    <select wire:model="status" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-cyan-500 transition-all">
                                        @foreach($statuses as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('status') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300">Applied Date</label>
                                    <input wire:model="applied_date" type="date" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-cyan-500 transition-all scheme-dark">
                                    @error('applied_date') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Salary & Link -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300">Salary <span class="text-slate-500 text-xs">(Optional)</span></label>
                                    <input wire:model="salary" type="text" placeholder="e.g. $120k - $150k" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-cyan-500 transition-all">
                                    @error('salary') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300">Job Link <span class="text-slate-500 text-xs">(Optional)</span></label>
                                    <input wire:model="link" type="text" placeholder="https://..." class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-cyan-500 transition-all">
                                    @error('link') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-300">Notes <span class="text-slate-500 text-xs">(Optional)</span></label>
                                <textarea wire:model="notes" rows="4" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-cyan-500 transition-all" placeholder="Interview notes, contact persons..."></textarea>
                                @error('notes') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-800">
                                <button 
                                    type="button"
                                    wire:click="closeModal"
                                    class="px-5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all duration-200"
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-xl text-white font-medium hover:from-cyan-400 hover:to-blue-500 transition-all duration-200 shadow-lg shadow-cyan-500/25"
                                >
                                    {{ $isEditing ? 'Update Application' : 'Track Application' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Modal -->
    <div 
        x-show="showDeleteModal" 
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center"
    >
        <div class="fixed inset-0 bg-black/50 backdrop-blur-md" @click="showDeleteModal = false"></div>
        <div class="relative bg-slate-800 rounded-lg p-6 border border-red-500/30 shadow-2xl max-w-sm w-full mx-4">
            <h3 class="text-lg font-bold text-white mb-2">Delete Application?</h3>
            <p class="text-slate-400 text-sm mb-6">Are you sure you want to delete this application? This cannot be undone.</p>
            <div class="flex justify-end space-x-3">
                <button 
                    @click="showDeleteModal = false" 
                    class="px-4 py-2 text-sm text-slate-400 hover:text-white hover:bg-slate-700 rounded-lg transition-all"
                >
                    Cancel
                </button>
                <button 
                    @click="$wire.delete(deleteId); showDeleteModal = false" 
                    class="px-4 py-2 text-sm bg-red-600 hover:bg-red-500 text-white rounded-lg transition-all shadow-lg shadow-red-600/20"
                >
                    Delete Forever
                </button>
            </div>
        </div>
    </div>
</div>
