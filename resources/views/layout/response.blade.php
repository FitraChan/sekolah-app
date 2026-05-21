<div class="mb-5">
  @if (session()->has('success'))
    <div class="alert alert-primary-soft show flex items-center mb-2" role="alert"> <i data-lucide="alert-circle" class="w-6 h-6 mr-2"></i>{{ session()->get('success') }} 
      <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x" class="w-4 h-4"></i> </button>
    </div>
  @endif
  
  @if (session()->has('error'))
    <div class="alert alert-danger-soft show flex items-center mb-2" role="alert"> <i data-lucide="alert-circle" class="w-6 h-6 mr-2"></i>{{ session()->get('error') }} 
      <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x" class="w-4 h-4"></i> </button>
    </div>
  @endif

</div>
