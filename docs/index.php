<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API de Agendamento - Documentação</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            text-align: center;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .main-content {
            padding: 2rem 0;
        }

        .section {
            background: white;
            margin-bottom: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .section-header {
            background: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .section-title {
            font-size: 1.5rem;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .section-description {
            color: #6c757d;
        }

        .endpoint {
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
        }

        .endpoint:last-child {
            border-bottom: none;
        }

        .endpoint-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .method {
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.875rem;
            margin-right: 1rem;
        }

        .method.get { background: #28a745; color: white; }
        .method.post { background: #007bff; color: white; }
        .method.put { background: #ffc107; color: #212529; }
        .method.delete { background: #dc3545; color: white; }

        .endpoint-path {
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
            color: #495057;
        }

        .auth-badge {
            background: #6f42c1;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            margin-left: 1rem;
        }

        .endpoint-description {
            margin-bottom: 1rem;
            color: #6c757d;
        }

        .parameters {
            margin-bottom: 1rem;
        }

        .parameters h4 {
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .parameter {
            display: flex;
            margin-bottom: 0.5rem;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }

        .parameter-name {
            font-weight: bold;
            color: #495057;
            min-width: 120px;
        }

        .parameter-type {
            color: #6c757d;
            margin: 0 0.5rem;
        }

        .parameter-description {
            color: #6c757d;
        }

        .example {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .example h4 {
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .code {
            background: #2d3748;
            color: #e2e8f0;
            padding: 1rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            overflow-x: auto;
        }

        .response {
            margin-top: 1rem;
        }

        .response h4 {
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .status-code {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: bold;
            margin-right: 0.5rem;
        }

        .status-200 { background: #d4edda; color: #155724; }
        .status-400 { background: #f8d7da; color: #721c24; }
        .status-401 { background: #f8d7da; color: #721c24; }
        .status-404 { background: #f8d7da; color: #721c24; }
        .status-500 { background: #f8d7da; color: #721c24; }

        .auth-section {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .auth-section h3 {
            color: #856404;
            margin-bottom: 0.5rem;
        }

        .auth-section p {
            color: #856404;
            margin-bottom: 0.5rem;
        }

        .footer {
            background: #495057;
            color: white;
            text-align: center;
            padding: 2rem 0;
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>API de Sistema de Agendamento</h1>
            <p>Documentação completa da API REST para gerenciamento de agendamentos</p>
        </div>
    </div>

    <div class="main-content">
        <div class="container">
            <div class="auth-section">
                <h3>🔐 Autenticação</h3>
                <p>Alguns endpoints requerem autenticação via API Key. Use uma das seguintes formas:</p>
                <p><strong>Header:</strong> X-API-Key: agendamento_api_key_2024</p>
                <p><strong>Query Parameter:</strong> ?api_key=agendamento_api_key_2024</p>
                <p><strong>Authorization Header:</strong> Bearer agendamento_api_key_2024</p>
            </div>

            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">📋 Catálogo e Disponibilidade</h2>
                    <p class="section-description">Endpoints públicos para consulta de serviços e horários disponíveis</p>
                </div>

                <div class="endpoint">
                    <div class="endpoint-header">
                        <span class="method get">GET</span>
                        <span class="endpoint-path">/api/services</span>
                    </div>
                    <div class="endpoint-description">
                        Lista todos os serviços disponíveis para agendamento.
                    </div>
                    <div class="response">
                        <h4>Resposta de Sucesso (200)</h4>
                        <div class="code">
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Corte Masculino",
      "description": "Corte de cabelo masculino tradicional",
      "duration": 30,
      "price": "25.00",
      "is_active": true
    }
  ]
}
                        </div>
                    </div>
                </div>

                <div class="endpoint">
                    <div class="endpoint-header">
                        <span class="method get">GET</span>
                        <span class="endpoint-path">/api/slots</span>
                    </div>
                    <div class="endpoint-description">
                        Retorna os horários disponíveis para um serviço específico em uma data.
                    </div>
                    <div class="parameters">
                        <h4>Parâmetros de Query</h4>
                        <div class="parameter">
                            <span class="parameter-name">service_id</span>
                            <span class="parameter-type">(int)</span>
                            <span class="parameter-description">ID do serviço</span>
                        </div>
                        <div class="parameter">
                            <span class="parameter-name">date</span>
                            <span class="parameter-type">(string)</span>
                            <span class="parameter-description">Data no formato YYYY-MM-DD</span>
                        </div>
                    </div>
                    <div class="example">
                        <h4>Exemplo de Requisição</h4>
                        <div class="code">GET /api/slots?service_id=1&date=2024-01-15</div>
                    </div>
                    <div class="response">
                        <h4>Resposta de Sucesso (200)</h4>
                        <div class="code">
{
  "success": true,
  "data": [
    {
      "time": "09:00",
      "datetime": "2024-01-15 09:00:00"
    },
    {
      "time": "09:30",
      "datetime": "2024-01-15 09:30:00"
    }
  ]
}
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">📅 Agendamentos</h2>
                    <p class="section-description">Gerenciamento de agendamentos - criação, cancelamento e atualização de status</p>
                </div>

                <div class="endpoint">
                    <div class="endpoint-header">
                        <span class="method post">POST</span>
                        <span class="endpoint-path">/api/appointments</span>
                    </div>
                    <div class="endpoint-description">
                        Cria um novo agendamento.
                    </div>
                    <div class="parameters">
                        <h4>Parâmetros do Body (JSON)</h4>
                        <div class="parameter">
                            <span class="parameter-name">service_id</span>
                            <span class="parameter-type">(int)</span>
                            <span class="parameter-description">ID do serviço (obrigatório)</span>
                        </div>
                        <div class="parameter">
                            <span class="parameter-name">date</span>
                            <span class="parameter-type">(string)</span>
                            <span class="parameter-description">Data no formato YYYY-MM-DD (obrigatório)</span>
                        </div>
                        <div class="parameter">
                            <span class="parameter-name">time</span>
                            <span class="parameter-type">(string)</span>
                            <span class="parameter-description">Horário no formato HH:MM (obrigatório)</span>
                        </div>
                        <div class="parameter">
                            <span class="parameter-name">client_name</span>
                            <span class="parameter-type">(string)</span>
                            <span class="parameter-description">Nome do cliente (obrigatório)</span>
                        </div>
                        <div class="parameter">
                            <span class="parameter-name">client_phone</span>
                            <span class="parameter-type">(string)</span>
                            <span class="parameter-description">Telefone do cliente (obrigatório)</span>
                        </div>
                        <div class="parameter">
                            <span class="parameter-name">client_email</span>
                            <span class="parameter-type">(string)</span>
                            <span class="parameter-description">Email do cliente (opcional)</span>
                        </div>
                        <div class="parameter">
                            <span class="parameter-name">professional_id</span>
                            <span class="parameter-type">(int)</span>
                            <span class="parameter-description">ID do profissional (opcional)</span>
                        </div>
                        <div class="parameter">
                            <span class="parameter-name">is_online</span>
                            <span class="parameter-type">(boolean)</span>
                            <span class="parameter-description">Se o agendamento foi feito online (opcional)</span>
                        </div>
                        <div class="parameter">
                            <span class="parameter-name">notes</span>
                            <span class="parameter-type">(string)</span>
                            <span class="parameter-description">Observações (opcional)</span>
                        </div>
                    </div>
                    <div class="example">
                        <h4>Exemplo de Requisição</h4>
                        <div class="code">
POST /api/appointments
Content-Type: application/json

{
  "service_id": 1,
  "date": "2024-01-15",
  "time": "09:00",
  "client_name": "João Silva",
  "client_phone": "(11) 99999-1111",
  "client_email": "joao@email.com",
  "professional_id": 1,
  "is_online": true,
  "notes": "Primeira vez"
}
                        </div>
                    </div>
                    <div class="response">
                        <h4>Resposta de Sucesso (200)</h4>
                        <div class="code">
{
  "success": true,
  "data": {
    "id": 123,
    "message": "Agendamento criado com sucesso"
  }
}
                        </div>
                    </div>
                </div>

                <div class="endpoint">
                    <div class="endpoint-header">
                        <span class="method delete">DELETE</span>
                        <span class="endpoint-path">/api/appointments/{id}</span>
                        <span class="auth-badge">Auth</span>
                    </div>
                    <div class="endpoint-description">
                        Cancela um agendamento existente.
                    </div>
                    <div class="response">
                        <h4>Resposta de Sucesso (200)</h4>
                        <div class="code">
{
  "success": true,
  "data": {
    "message": "Agendamento cancelado com sucesso"
  }
}
                        </div>
                    </div>
                </div>

                <div class="endpoint">
                    <div class="endpoint-header">
                        <span class="method put">PUT</span>
                        <span class="endpoint-path">/api/appointments/{id}/status</span>
                        <span class="auth-badge">Auth</span>
                    </div>
                    <div class="endpoint-description">
                        Atualiza o status de um agendamento.
                    </div>
                    <div class="parameters">
                        <h4>Parâmetros do Body (JSON)</h4>
                        <div class="parameter">
                            <span class="parameter-name">new_status</span>
                            <span class="parameter-type">(string)</span>
                            <span class="parameter-description">Novo status: pending, confirmed, completed, cancelled</span>
                        </div>
                    </div>
                    <div class="example">
                        <h4>Exemplo de Requisição</h4>
                        <div class="code">
PUT /api/appointments/123/status
Content-Type: application/json

{
  "new_status": "confirmed"
}
                        </div>
                    </div>
                    <div class="response">
                        <h4>Resposta de Sucesso (200)</h4>
                        <div class="code">
{
  "success": true,
  "data": {
    "message": "Status atualizado com sucesso"
  }
}
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">⚙️ Administração</h2>
                    <p class="section-description">Endpoints administrativos para gerenciamento da agenda e configurações</p>
                </div>

                <div class="endpoint">
                    <div class="endpoint-header">
                        <span class="method get">GET</span>
                        <span class="endpoint-path">/api/admin/schedule</span>
                        <span class="auth-badge">Auth</span>
                    </div>
                    <div class="endpoint-description">
                        Lista todos os agendamentos em um período específico.
                    </div>
                    <div class="parameters">
                        <h4>Parâmetros de Query</h4>
                        <div class="parameter">
                            <span class="parameter-name">start_date</span>
                            <span class="parameter-type">(string)</span>
                            <span class="parameter-description">Data inicial no formato YYYY-MM-DD (opcional)</span>
                        </div>
                        <div class="parameter">
                            <span class="parameter-name">end_date</span>
                            <span class="parameter-type">(string)</span>
                            <span class="parameter-description">Data final no formato YYYY-MM-DD (opcional)</span>
                        </div>
                    </div>
                    <div class="response">
                        <h4>Resposta de Sucesso (200)</h4>
                        <div class="code">
{
  "success": true,
  "data": [
    {
      "id": 123,
      "service_name": "Corte Masculino",
      "professional_name": "João Silva",
      "client_name": "Maria Santos",
      "client_phone": "(11) 99999-1111",
      "appointment_date": "2024-01-15",
      "appointment_time": "09:00:00",
      "status": "confirmed",
      "duration": 30,
      "price": "25.00"
    }
  ]
}
                        </div>
                    </div>
                </div>

                <div class="endpoint">
                    <div class="endpoint-header">
                        <span class="method get">GET</span>
                        <span class="endpoint-path">/api/admin/professionals</span>
                        <span class="auth-badge">Auth</span>
                    </div>
                    <div class="endpoint-description">
                        Lista todos os profissionais ativos.
                    </div>
                    <div class="response">
                        <h4>Resposta de Sucesso (200)</h4>
                        <div class="code">
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "João Silva",
      "email": "joao@salon.com",
      "phone": "(11) 99999-1111",
      "specialties": "Cortes masculinos, Barba",
      "is_active": true
    }
  ]
}
                        </div>
                    </div>
                </div>

                <div class="endpoint">
                    <div class="endpoint-header">
                        <span class="method get">GET</span>
                        <span class="endpoint-path">/api/admin/settings</span>
                        <span class="auth-badge">Auth</span>
                    </div>
                    <div class="endpoint-description">
                        Obtém as configurações gerais do sistema.
                    </div>
                    <div class="response">
                        <h4>Resposta de Sucesso (200)</h4>
                        <div class="code">
{
  "success": true,
  "data": {
    "business_hours": [
      {
        "day_of_week": 1,
        "open_time": "09:00:00",
        "close_time": "18:00:00"
      }
    ],
    "timezone": "America/Sao_Paulo",
    "default_interval": 30
  }
}
                        </div>
                    </div>
                </div>

                <div class="endpoint">
                    <div class="endpoint-header">
                        <span class="method put">PUT</span>
                        <span class="endpoint-path">/api/admin/settings</span>
                        <span class="auth-badge">Auth</span>
                    </div>
                    <div class="endpoint-description">
                        Atualiza as configurações gerais do sistema.
                    </div>
                    <div class="parameters">
                        <h4>Parâmetros do Body (JSON)</h4>
                        <div class="parameter">
                            <span class="parameter-name">business_hours</span>
                            <span class="parameter-type">(array)</span>
                            <span class="parameter-description">Array com horários de funcionamento</span>
                        </div>
                    </div>
                    <div class="example">
                        <h4>Exemplo de Requisição</h4>
                        <div class="code">
PUT /api/admin/settings
Content-Type: application/json

{
  "business_hours": [
    {
      "day_of_week": 1,
      "open_time": "09:00:00",
      "close_time": "18:00:00"
    },
    {
      "day_of_week": 2,
      "open_time": "09:00:00",
      "close_time": "18:00:00"
    }
  ]
}
                        </div>
                    </div>
                    <div class="response">
                        <h4>Resposta de Sucesso (200)</h4>
                        <div class="code">
{
  "success": true,
  "data": {
    "message": "Configurações atualizadas com sucesso"
  }
}
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">📊 Códigos de Status HTTP</h2>
                    <p class="section-description">Códigos de resposta utilizados pela API</p>
                </div>
                <div style="padding: 1.5rem;">
                    <div class="parameter">
                        <span class="status-code status-200">200</span>
                        <span class="parameter-description">Sucesso - Operação realizada com sucesso</span>
                    </div>
                    <div class="parameter">
                        <span class="status-code status-400">400</span>
                        <span class="parameter-description">Erro de validação - Dados inválidos ou campos obrigatórios ausentes</span>
                    </div>
                    <div class="parameter">
                        <span class="status-code status-401">401</span>
                        <span class="parameter-description">Não autorizado - API Key inválida ou ausente</span>
                    </div>
                    <div class="parameter">
                        <span class="status-code status-404">404</span>
                        <span class="parameter-description">Não encontrado - Recurso solicitado não existe</span>
                    </div>
                    <div class="parameter">
                        <span class="status-code status-500">500</span>
                        <span class="parameter-description">Erro interno - Erro no servidor</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <p>&copy; 2024 Sistema de Agendamento - API Documentation</p>
        </div>
    </div>
</body>
</html>
