@extends('home')

@section('content')
<p class="text-left back"><a href="{{url('/')}}">&lt;&lt; Tillbaka</a></p>
<div class="accordion" id="faqsAccordion">
<p class="lead"><h4 class="my-0 font-weight-normal text-center">Vanliga Frågor</h4></p>

  <div class="card">
    <div class="card-header">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-justify" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          Hur gör man för att ta prov med ett självprovtagnignskit?
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse" data-parent="#faqsAccordion">
      <div class="card-body">
      	  Se instruktionen som skickas med kitet hem till dig: <a target="_blank" href="{{asset('storage/files/Screening_hpv_original_rev_201126-persetiketthpåse.pdf')}}">Instruktionsfil för självprovtagning HPV</a>
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="card-header">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-justify" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Varför har jag fått tillfrågan om att delta i denna studie?
        </button>
      </h2>
    </div>

    <div id="collapseTwo" class="collapse" data-parent="#faqsAccordion">
      <div class="card-body">
      	Vi vill få kunskap om möjlighet att beställa självprovtagningskit är ett bra sätt att öka deltagandet i gynekologisk cellprovtagning och i förlängningen minska risken för livmoderhalscancer.
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="card-header">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-justify" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Vem utför studien?
        </button>
      </h2>
    </div>

    <div id="collapseThree" class="collapse" data-parent="#faqsAccordion">
      <div class="card-body">
      	Studien genomförs av Karolinska Institutet och Nationell Arbetsgrupp för Cervixcancerprevention, i samarbete med Karolinska Universitetssjukhuset.
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="card-header">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-justify" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
          Jag är över 70 år, kan jag vara med i studien?
        </button>
      </h2>
    </div>

    <div id="collapseFour" class="collapse" data-parent="#faqsAccordion">
      <div class="card-body">
      	I 2015 års screeningprogram anges att man ska tillse att alla upp till 70 år fått adekvat screening. Vi kan se att trots att det gått >5 år sedan det nya screeningprogrammet beslutades så har många kvinnor hunnit bli äldre än 70 år och hunnit lämna screeningprogrammet utan tillräcklig screening. Vi är därför i synnerhet intresserade av om det går att nå ut till denna grupp med självprovtagning.	
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="card-header">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-justify" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
          Hur får jag veta resultatet på mitt HPV test?
        </button>
      </h2>
    </div>

    <div id="collapseFive" class="collapse" data-parent="#faqsAccordion">
      <div class="card-body">
      	När ditt prov är färdiganalyserat kommer du att få ett SMS med länk till vår hemsida, där du kan logga in med Mobilt BankID för att ta del av ditt resultat.
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="card-header">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-justify" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
          Jag har inte Mobilt BankID, hur kan jag ta del av mitt resultat?
        </button>
      </h2>
    </div>

    <div id="collapseSix" class="collapse" data-parent="#faqsAccordion">
      <div class="card-body">
      	Kontakta oss i första hand med SMS till 0725836709 eller maila till <a href="mailto:hpvcenter@ki.se">hpvcenter@ki.se</a>	
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="card-header">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-justify" type="button" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
          Hur gör jag för att komma i kontakt med forskargruppen?
        </button>
      </h2>
    </div>

    <div id="collapseSeven" class="collapse" data-parent="#faqsAccordion">
      <div class="card-body">
      	Skicka SMS vardagar till 0725836709, alternativt maila <a href="mailto:hpvcenter@ki.se">hpvcenter@ki.se</a>	
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="card-header">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-justify" type="button" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
          Kostar det något att delta i studien?
        </button>
      </h2>
    </div>

    <div id="collapseEight" class="collapse" data-parent="#faqsAccordion">
      <div class="card-body">
      	Nej, deltagande är gratis. Studien finansieras av forskningsanslag från Cancerfonden.	
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="card-header">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-justify" type="button" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
          Vilka tillstånd har studien?
        </button>
      </h2>
    </div>

    <div id="collapseNine" class="collapse" data-parent="#faqsAccordion">
      <div class="card-body">
      	Studien utförs med tillstånd från Etikprövningsmyndigheten.	
      </div>
    </div>
  </div>

</div>

@endsection




@section('scripts')
<script type="text/javascript">
    /*$("#ordersAccordion").on('show.bs.collapse', function (e) {
    	$(e.target).prev('.card-header').find('.btn').addClass('font-weight-bold');
    });
    
    $('#ordersAccordion').on('hide.bs.collapse', function (e) {
    	$(this).find('.btn').not($(e.target)).removeClass('font-weight-bold');
    });*/
</script>
@endsection
