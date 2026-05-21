@extends('layout.main')
@section('tittle')
  Dashboard
@endsection

@section('top-nav')
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="#">Dashboard</a></li>
	
</ol>
@endsection

@section('body')
	<div class="col-span-12 mt-8">
		<div class="intro-y flex items-center h-10">
				<h2 class="text-lg font-medium truncate mr-5">
					Dashboard 
				</h2>
				<a href="" class="ml-auto flex items-center text-primary"> <i data-lucide="refresh-ccw" class="w-4 h-4 mr-3"></i> Reload Data </a>
		</div>
		<div class="grid grid-cols-12 gap-6 mt-5">
				<div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
						<div class="report-box zoom-in">
								<div class="box p-5">
										<div class="flex">
												<i data-lucide="user" class="report-box__icon text-primary"></i>
										</div>
										<div class="text-3xl font-medium leading-8 mt-6"></div>
										<div class="text-base text-slate-500 mt-1">Jumlah Siswa Wali</div>
										
								</div>
						</div>
				</div>
				<div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
						<div class="report-box zoom-in">
								<div class="box p-5">
										<div class="flex">
												<i data-lucide="monitor" class="report-box__icon text-pending"></i> 
												
										</div>
										
										<div class="text-base text-slate-500 mt-1">Kelas Wali</div>
								</div>
						</div>
				</div>
				<div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
					<a href="#">
						<div class="report-box zoom-in">
								<div class="box p-5">
										<div class="flex">
												<i data-lucide="briefcase" class="report-box__icon text-dark"></i> 
												
										</div>
										
								</div>
						</div>
					</a>
				</div>
				
		</div>
	</div>
@endsection