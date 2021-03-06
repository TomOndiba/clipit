<?php
/**
 * Clipit Web Space
 * PHP version:     >= 5.2
 * Creation date:   22/04/14
 * Last update:     22/04/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC Clipit Project
 * @version         $Version$
 * @link            http://clipit.es
 * @license         GNU Affero General Public License v3
 * @package         Clipit
 */
$spanish = array(
    'user:profile:avatar'=> 'Avatar del usuario',
    'optional' => 'Opcional',
    'options:advanced' => 'Opciones avanzadas',
    'refresh' => 'Actualizar',
    'ok' => 'Ok',
    'done' => 'Hecho',
    'change' => 'Cambiar',
    'created' => 'Creado',
    'calendar' => 'Calendario',
    'list' => 'Lista',
    'selected' => 'Seleccionado',
    'unselected' => 'Sin seleccionar',
    'review' => 'Revisión',
    'add:more' => 'Añadir más',
    'check:all_none'=> 'Seleccionar todos/ninguno',
    'select:type'=> 'Seleccionar tipo',
    'stats' => 'Estadísticas',
    'students' => 'Estudiantes',
    'groups' => 'Grupos',
    'search:btn' => 'Buscar',
    'status' => 'Estado',
    'send:to_site' => 'Publicar',
    'send:to_global' => 'Publicar también Globalmente',
    'send:to_global:question' => '¿Publicar Globalmente?',
    'send:to_site:input' => 'Al sitio',
    'send:to_global:input' => 'Al sitio y globalmente',
    'time:days' => 'Días',
    'time' => 'Hora',
    'time:hours' => 'Horas',
    'time:minutes' => 'Minutos',

    // Roles
    'teacher' => 'Profesor',
    'student' => 'Estudiante',
    'admin' => 'Administrador',

    'change_to' => 'Cambiar a',
    'current_status' => 'Estado actual',
    'exit:page:confirmation' => 'Si abandona la página, los datos que ha introducido no se guardaran',
    'users:none' => 'No hay estudiantes',
    'start' => 'Inicio',
    'end' => 'Fin',
    'expand:all' => 'Expandir todos',
    'collapse:all' => 'Cerrar todos',
    'click_add' => 'Click para añadir',
    'view'  => 'Ver',
    'bulk_actions' => 'Acciones en lote',
    // Activity
    'activity' => 'Actividad',
    'activity:join' => 'Entrar',
    'activity:joined' => 'Entrado',
    'activity:status' => 'Progreso de las actividades',
    'activity:title' => 'Nombre de la actividad',
    'activity:description' => 'Descripción de la actividad',
    'activity:start' => 'Comienzo',
    'activity:end' => 'Final',
    'activity:select:tricky_topic' => 'Tema clave',
    'activity:overview' => 'Visión general',
    'activity:edit' => 'Editar Actividad',
    'activity:admin' => 'Administración',
    'activity:register:title' => 'Tipo actividad',
    'activity:register:info' => 'Cualquier estudiante podrá unirse a la actividad antes de la fecha de comienzo.',
    'activity:register:students_per_group' => 'Alumnos por grupo',
    'activity:register:max_students' => 'Máximo de estudiantes en la actividad',
    'activity:register:max_students:info' => '0 = Sin límite de estudiantes',
    'activity:register:open' => 'Abierta',
    'activity:register:closed' => 'Cerrado',
    'my_activities' => 'Mis actividades',
    'my_activities:active' => 'Mis actividades en curso',
    'my_activities:none' => 'Actualmente no hay actividades en curso',
    'activities' => 'Actividades',
    'activities:open' => 'Actividades públicas',
    'activities:none' => 'No se han encontrado actividades',
    'explore' => 'Explorar',
    'activity:delete' => 'Eliminar actividad',
    'activity:create' => 'Crear actividad',
    'activity:create:info:title' => 'Al crear una actividad, es necesario especificar',
    'activity:create:info:step:1' => 'El <b>Tema Clave</b> asociado: uno por actividad',
    'activity:create:info:step:2' => '<b>Información sobre la Actividad</b>: nombre, descripción y fechas',
    'activity:create:info:step:3' => '<b>Tareas</b> incluidas: descripción, tipo y fechas',
    'activity:create:info:step:3:details' => '
        <p>Algunas tareas usan recursos de profesor previamente creado desde el menú "Herramientas de autoría", p.ej.: las tareas de tipo "Test" deben estar enlazadas a un test, y las de tipo "Ver materiales del profesor" requieren que el Tema Clave contenga materiales docentes.</p>
        <p>Por favor, entra en las "Herramientas de autoría" y asegúrate de haber creado todos los recursos del profesor necesarios antes de crear estas tareas.</p>
        <p>También puedes crear una actividad sin algunas tareas, y añadirlas más tarde, después de haber añadido recursos necesarios usando las "Herramientas de autoría".</p>
    ',
    'activity:create:info:step:4' => '<b>Estudiantes</b> que participarán: cuentas y cómo se agruparán',

    'activity:profile' => 'Inicio',
    'activity:progress' => 'Progreso de la actividad',
    'activity:groups' => 'Grupos',
    'activity:discussion' => 'Discusiones',
    'activity:stas' => 'Materiales del profesor',
    'activity:publications' => 'Publicaciones',
    'activity:button:join' => 'Entrar en la actividad',
    'activity:group:join' => 'Unirse a un grupo',
    'activity:upcoming_tasks' => 'Próximas tareas',
    'activity:pending_tasks' => 'Tareas pendientes',
    'activity:next_deadline' => 'Próxima tarea',
    'activity:quiz' => 'Test de la actividad',
    'activity:teachers' => 'Profesores',
    'activity:invited' => 'Ha sido invitado a la actividad',
    // Activity status
    'status:enroll' => 'Inscripción',
    'status:active' => 'En curso',
    'status:closed' => 'Finalizada',
    'status:change_to:active:tooltip' => 'La fecha de inicio se establecerá como hoy. Ajuste la fecha de finalización manualmente. Haga click en Guardar para aceptar los cambios.',
    'status:change_to:closed:tooltip' => 'La fecha de finalización se establecerá como hoy. Haga clic en Guardar para aceptar los cambios.',

    'group' => 'Grupo',
    'my_group:progress' => 'Progreso de mis grupos',
    'my_group:none' => 'No estás en ningún grupo de la actividad.',
    'group:free_slot' => 'Quedan <strong><u>%s</u></strong> huecos libres',
    'group:assign_sb' => 'Asignar conceptos',
    'group:graph' => 'Gráfica del grupo',
    'group:max_size' => 'Máximo de estudiantes por grupo',
    'group:ungrouped' => 'Sin agrupar',
    'group:activity' => 'Progreso de los grupos',
    'group:name' => 'Nombre del grupo',
    'group:create' => 'Crear grupo',
    'group:join' => 'Unirse',
    'group:leave' => 'Abandonar',
    'group:full' => 'Completo',
    'group:leave:me' => 'Dejar el grupo',
    'group:cantcreate' => 'No puedes crear un grupo.',
    'group:created' => 'Grupo creado',
    'group:joined' => '¡Te has unido correctamente al grupo!',
    'group:cantjoin' => 'No puedes unirte al grupo',
    'group:left' => 'Has abandonado correctamente el grupo',
    'group:cantleave' => 'No puedes abandonar el grupo',
    'group:member:remove' => 'Eliminar del grupo',
    'group:member:cantremove' => 'No se puede borrar el miembro del grupo',
    'group:member:removed' => '%s eliminado correctamente del grupo',
    'group:added' => 'Grupo añadido a la actividad',
    'groups:none' => 'No hay grupos',
    // Quizz
    'quiz' => 'Test',

    // Group tools
    'group:menu' => 'Menú del grupo',
    'group:tools' => 'Herramientas de grupo',
    'group:discussion' => 'Discusiones del grupo',
    'group:files' => 'Materiales del grupo',
    'group:home' => 'Inicio',
    'group:activity_log' => 'Registro de la actividad',
    'group:progress' => 'Progreso del grupo',
    'group:timeline' => 'Cronología',
    'group:members' => 'Miembros',
    'group:students' => 'Estudiantes del grupo',
    // Discussion
    'discussions:none' => 'No hay discusiones',
    'discussion:start' => 'Comenzar una discusión',
    'discussion:multimedia:go' => 'Ir a la discusión',
    'discussion:create' => 'Crear un nuevo tema',
    'discussion:created' => 'Discusión creada',
    'discussion:deleted' => 'Discusión eliminada',
    'discussion:cantdelete' => 'La discusión no se puede borrar',
    'discussion:cantcreate' => 'No se ha podido crear la discusión',
    'discussion:edited' => 'Discusión actualizada',
    'discussion:edit' => 'Editar tema',
    'discussion:title_topic' => 'Título',
    'discussion:text_topic' => 'Texto',
    'discussion:last_post_by' => 'Último mensaje por',
    'discussion:created_by' => 'Creado por',
    // Multimedia
    'url'   => 'Url',
    'multimedia:files' => 'Archivos',
    'multimedia:file_uploaded' => 'Archivo subido',
    'multimedia:videos' => 'Videos',
    'multimedia:links' => 'Links de interés',
    'multimedia:attach' => 'Adjuntar material',
    'multimedia:attach_group' => 'Adjuntar material del grupo',
    'multimedia:attach_files' => 'Adjuntar archivos',
    'multimedia:uploaded_by' => 'Subido por',
    'multimedia:delete' => 'Eliminar',
    'multimedia:processing' => 'Procesando',
    'multimedia:attachments' => 'materiales adjuntos',
    'multimedia:attachment' => 'material adjunto',
    'attachments' => 'adjunto(s)',
    // Files
    'files' => 'Archivos',
    'file' => 'Archivo',
    'file:download' => 'Descargar',
    'file:uploaded' => 'Archivo subido',
    'multimedia:file:name' => 'Nombre del archivo',
    'multimedia:file:description' => 'Descripción',
    'multimedia:files:add' => 'Añadir archivos',
    'file:delete' => 'Borrar archivos',
    'file:nofile' => 'Sin archivo',
    'file:removed' => 'El archivo %s ha sido borrado',
    'file:cantremove' => 'No se puede borrar el archivo',
    'file:edit' => 'Editar archivo',
    'file:edited' => 'Archivo editado',
    'file:added' => 'Archivo añadido',
    'file:none' => "No hay archivos",
    'files:none' => "No hay archivos",
    /* File types */
    'file:general' => 'Archivo',
    'file:document' => 'Documento',
    'file:image' => 'Imagen',
    'file:video' => 'Video',
    'file:audio' => 'Audio',
    'file:compressed' => 'Archivo comprimido',
    // Videos
    'videos' => 'Videos',
    'video' => 'Video',
    'videos:recommended' => 'Videos recomendados',
    'videos:recommended:none' => 'No se han encontrado videos recomendados',
    'videos:related' => 'Videos relacionados',
    'videos:none' => 'No hay videos',
    'video:url:error' => 'Incorrect url or video not found',
    'video:edit' => 'Editar video',
    'video:edited' => 'Video editado',
    'video:add' => 'Añadir video',
    'video:added' => 'Video añadido correctamente',
    'video:deleted' => 'Video eliminado',
    'video:cantadd' => 'No se ha podido añadir el video',
    'video:add:to_youtube' => 'Subir video a Youtube',
    'video:add:paste_url' => 'Pegar dirección url de Youtube o Vimeo',
    'video:link:youtube_vimeo' => 'Dirección url de Youtube o Vimeo',
    'video:uploading:youtube' => 'Subiendo a youtube',
    'video:url' => 'Url del video',
    'video:upload' => 'Añadir video',
    'video:uploaded' => 'Video añadido',
    'video:title' => 'Título del video',
    'video:tags' => 'Conceptos del video',
    'video:description' => 'Descripción del video',
    // Tricky Topic
    'tricky_topic' => 'Tema clave',
    'tricky_topic:none' => 'No hay temas clave',
    'tricky_topic:tool' => 'Herramienta creación de temas clave',
    'tricky_topic:select' => 'Seleccionar tema clave',
    'tricky_topic:created_by_me' => 'Creados por mi',
    'tricky_topic:created_by_others' => 'Otros',
    // Publications
    'publish:none' => 'No hay nada publicado',
    'publications:no_evaluated' => 'Sin evaluar',
    'publications:evaluated' => 'Evaluado',
    'publications:rating' => 'Puntuación',
    'publications:rating:name' => 'Puntuación de %s',
    'publications:rating:list' => 'Todas las evaluaciones',
    'publications:rating:edit' => 'Editar evaluación',
    'publications:rating:votes' => 'VOTOS',
    'publications:rating:my_evaluation' => 'Mi valoración',
    'publications:rating:stars' => 'Valora de 1 a 5 la realización del video, teniendo en cuenta',
    'publications:starsrequired' => 'Las estrellas de puntuación son obligatorias',
    'publications:cantrating' => 'No has podido puntuar',
    'publications:rated' => 'Evaluado correctamente',
    'publications:my_rating' => 'Mi puntuación',
    'publications:evaluate' => 'Evaluar',
    'publications:question:tricky_topic' => '¿Te ha ayudado a entender el Tema clave %s?',
    'publications:question:sb' => '¿Por qué este concepto está/no está correctamente explicado?',
    'publications:question:if_covered' => '¿Se han explicado los siguientes conceptos correctamente? (explica por qué)',
    'publications:view_scope' => 'Ver en',
    'publications:review:info' => 'Revisa el contenido y haz click en Seleccionar',
    'publications:select:tooltip' => 'Haz click para revisar el contenido y seleccionarlo para la tarea',
    'ratings:none' => 'No hay feedback',
    'rating:stars:1' => 'Nulo',
    'rating:stars:2' => 'Pobre',
    'rating:stars:3' => 'Razonable',
    'rating:stars:4' => 'Bueno',
    'rating:stars:5' => 'Excelente',
    'input:no' => 'No',
    'input:yes' => 'Si',
    'input:ok' => 'Continuar',
    'publish'   => 'Publicar',
    'published'   => 'Publicado',
    'publish:to_activity'   => 'Publicar %s en %s',
    'publish:video'   => 'Publicar videos',
    // Labels
    'label' => 'Etiqueta',
    'labels' => 'Etiquetas',
    'labels:none' => 'No hay etiquetas añadidas',
    'labels:added' => 'Etiqueta/s añadida/s',
    'labels:cantadd' => 'No se ha podido añadir',
    'labels:cantadd:empty' => 'No se ha podido añadir una etiqueta vacia',
    // Tags
    'tag' => 'Concepto',
    'tags' => 'Conceptos',
    'tags:add' => 'Añadir conceptos',
    'tags:assign' => 'Asignar conceptos',
    'tags:none' => 'No hay conceptos',
    'tags:recommended' => 'Conceptos recomendados',
    'tags:commas:separated' => 'Separado por comas',
    // Performance items
    'performance_item' => 'Recurso artístico',
    'performance_items' => 'Recursos artísticos',
    'performance_item:select' => 'Seleccionar recursos artísticos',
    // Tasks
    'activity:tasks' => 'Tareas',
    'activity:task' => 'Tarea',
    'task:title' => 'Título de la tarea',
    'task:title:page' => 'Tarea de %s',
    'task:add' => 'Añadir tarea',
    'task:remove' => 'Eliminar tarea',
    'task:remove_video' => 'Quitar video',
    'task:remove_file' => 'Quitar archivo',
    'task:added' => 'Tarea añadida',
    'task:updated' => 'Tarea actualizada',
    'task:created' => 'Tarea creada',
    'task:removed' => 'Tarea eliminada',
    'task:cantupdate' => 'No se ha podido actualizar la tarea',
    'task:cantcreate' => 'No se ha podido crear la tarea',
    'task:template:title' => 'Usar plantilla',
    'task:template:select' => 'Seleccionar plantilla',
    'task:user' => 'Tarea invidual',
    'task:group' => 'Tarea grupal',
    'task:select' => 'Seleccionar tarea',
    'task:select:task_type' => 'Seleccionar tipo de tarea',
    'task:task_type' => 'Tipo de tarea',
    'task:resource_download' => 'Ver/Descargar materiales',
    'task:resource_download:select' => 'Selecciona los materiales para esta tarea',
    'task:feedback' => 'Tarea de feedback',
    'task:feedback:linked' => 'Tarea de feedback vinculada',
    'task:feedback:check' => 'Añadir una tarea de feedback',
    'tasks:none' => 'No hay tareas',
    'task:completed' => 'Completada',
    'task:not_completed' => 'Sin completar',
    'task:next' => 'Próximas tareas',
    'task:pending' => 'Pendiente',
    'task:my_video' => 'Video de mi grupo',
    'task:other_videos' => 'Videos de otros grupos',
    'task:my_file' => 'Archivo de mi grupo',
    'task:other_files' => 'Archivos de otros grupos',
    'task:not_actual' => 'No hay tareas pendientes',
    'task:video_upload' => 'Publicar videos',
    'task:file_upload' => 'Publicar archivos',
    'task:file_uploaded' => 'Archivo subido',
    'task:text_upload' => 'Publicar textos',
    'task:text_uploaded' => 'Texto publicado',
    'task:quiz_take' => 'Test',
    'task:quiz_answer' => 'Test',
    'task:quiz_take:select' => 'Debes seleccionar un Test de la lista. <br>(Si no hay Tests debes crear uno)',
    'task:video_feedback' => 'Feedback sobre video',
    'task:file_feedback' => 'Feedback sobre archivos',
    'task:other' => 'Otra',
    'task:videos:none' => 'Añade un video para poder seleccionar',
    'task:files:none' => 'Sube un archivo para poder seleccionar',
    'repository:group' => 'materiales del grupo',
    'task:create' => 'Crear nueva tarea',
    'task:edit' => 'Editar tarea',
    'task:group:needed' => 'Necesitas estar en un grupo para hacer la tarea, contacta con tu/s profesor/es',
    /// Task status
    'task:locked' => 'Tarea actualmente cerrada',
    'task:active' => 'Tarea abierta',
    'task:finished' => 'Tarea terminada',
    'rating:none' => 'No hay puntuacion',
    // Create activity
    'or:create' => 'o crea un',
    'activity:site:students' => 'Estudiantes de Clipit',
    'activity:students' => 'Estudiantes de la actividad',
    'activity:select' => 'Seleccionar actividad',
    'finish' => 'Terminar',
    'teachers:add' => 'Añadir profesores',
    'students:add' => 'Añadir estudiantes',
    'users:create' => 'Crear usuarios',
    'teacher:addedresource' => 'Ha añadido un material a la actividad',
    'called:students:add' => 'Añadir estudiantes manualmente',
    'called:students:add:from_excel' => 'Añadir estudiantes desde un archivo Excel',
    'called:students:insert_to_site' => 'Añadir a Clipit',
    'called:students:insert_to_activity' => 'Añadir a la actividad',
    'activity:grouping_mode' => 'Modo de creación de grupos',
    'activity:grouping_mode:teacher' => 'El profesor hace los grupos',
    'activity:grouping_mode:teacher:desc' => 'Después de crear la actividad, puede agregar los alumnos en grupos desde la página de administración',
    'activity:grouping_mode:student' => 'Los estudiantes hacen los grupos',
    'activity:grouping_mode:system' => 'Crear grupos aleatoriamente',
    'activity:download:excel_template' => 'Descargar plantilla Excel',
    'called:students:excel_template' => 'Plantilla Excel',
    'called:students:add_from_site' => 'Añadir desde Clipit',
    'called:students:add_from_excel' => 'Añadir desde un archivo Excel',
    'called:students:add_from_excel:waiting' => 'Espere mientras se está subiendo los estudiantes al sistema',
    'activity:created' => 'La actividad %s ha sido creada',
    'search:filter' => 'Filtrar',
    // Activity admin
    'activity:deleted' => 'Actividad eliminada',
    'activity:cantdelete' => 'No se ha podido eliminar la actividad',
    'activity:admin:info' => 'Información',
    'activity:admin:task_setup' => 'Tareas',
    'activity:admin:groups' => 'Estudiantes',
    'activity:admin:setup' => 'Configuración',
    'activity:admin:options' => 'Opciones',
    'activity:admin:videos' => 'Videos',
    'groups:select:move' => 'mover al grupo...',
    'clipit:or' => 'o',
    'activity:updated' => 'Actividad actualizada',
    'activity:cantupdate' => 'No se ha podido actualizar la actividad',
    // resources trial DCM
    'resources' => 'Enlaces',
    'resource' => 'Enlace',
    'resource:add' => 'Añadir enlaces',
    'resource:title' => 'Título',
    'resource:description' => 'Enlaces y descripción',
    'resource:added' => 'Enlaces añadidos',
    'resource:deleted' => 'Enlaces borrados',
    'resources:none' => 'No hay enlaces',
    'task:resource_upload' => 'Publicar enlaces',
    'task:resource_feedback' => 'Feedback sobre enlaces',
    'task:resources:none' => 'Añade un enlace a %s para poder publicar',
    'task:other_resources' => 'Enlaces de otros grupos',
    'task:my_resource' => 'Mi enlace',
    'resource:event:added' => 'Ha añadido un enlace al grupo',
    'resource:added_by' => 'Añadido por',

    // Quiz
    'quiz:teacher_annotation' => 'Comentario del profesor',
    'quiz:data:none' => 'No hay datos',
    'quiz:not_finished' => 'Sin terminar',
    'quiz:tricky_topic:danger' => 'Si cambia de Tema Clave se borrarán las preguntas creadas',
    'difficulty' => 'Dificultad',
    'quiz:select:from_tag' => 'Añadir preguntas existentes relacionadas',
    'quiz:question' => 'Pregunta',
    'quiz:questions' => 'Preguntas',
    'quiz:question:add' => 'Crear una pregunta',
    'quiz:not_started' => 'Sin empezar',
    'quiz:finished' => 'Terminado',
    'quiz:time:to_do' => 'Tiempo para hacer el examen',
    'quiz:time:finish' => 'Termina a las',
    'quiz:question:answered' => 'Contestado',
    'quiz:question:not_answered' => 'No contestado',
    'quiz:question:annotate' => 'Añadir anotación',
    'quiz:question:results' => 'Resultados',
    'quiz:question:result:add' => 'Añadir respuesta',
    'quiz:question:answer' => 'Respuesta',
    'quiz:question:type' => 'Tipo de pregunta',
    'quiz:question:statement' => 'Enunciado de la pregunta',
    'quiz:question:additional_info' => 'Información adicional',
    'quiz:question:add_video' => 'Añadir enlace a video',
    'quiz:question:add_image' => 'Adjuntar imagen',
    'quiz:question:remove_image' => 'Eliminar imagen',
    'quiz:question:add_image:valid_extension' => 'Estensiones válidas',
    'quiz:questions:answered' => 'Preguntas contestadas',
    'quiz:questions:answers:correct' => 'preguntas correctas',
    'quiz:answer:solution' => 'Solución',
    'quiz:results:stumbling_block' => 'Resultados por Conceptos',
    'quiz:out_of' => 'de',
    'quiz:participants' => 'Participantes',
    'quiz:progress' => 'Progreso',
    'quiz:score' => 'Puntuación',
    'calendar:month_names'=> json_encode(array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre')),
    'calendar:month_names_short'=> json_encode(array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic')),
    'calendar:day_names'=> json_encode(array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado')),
    'calendar:day_names_short'=> json_encode(array('Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb')),
    'calendar:day_names_min'=> json_encode(array('Do','Lu','Ma','Mi','Ju','Vi','Sá')),
    'calendar:month' => 'Mes',
    'calendar:day' => 'Día',
    'calendar:week' => 'Semana',
    'calendar:list' => 'Agenda',
);

add_translation('es', $spanish);
