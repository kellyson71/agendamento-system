# Sistema de Agendamento - API PHP

Sistema completo de agendamento desenvolvido em PHP puro com API REST para gerenciamento de serviços, horários disponíveis e agendamentos.

## 🚀 Características

- **API REST completa** em PHP puro
- **Banco de dados MySQL** com estrutura otimizada
- **Sistema de autenticação** via API Key
- **Documentação interativa** estilo Swagger
- **Validação de dados** e tratamento de erros
- **CORS habilitado** para desenvolvimento
- **Testes automatizados** incluídos

## 📋 Funcionalidades

### Endpoints Públicos
- `GET /api/services` - Lista serviços disponíveis
- `GET /api/slots` - Horários disponíveis para agendamento
- `POST /api/appointments` - Criar novo agendamento

### Endpoints Administrativos
- `DELETE /api/appointments/{id}` - Cancelar agendamento
- `PUT /api/appointments/{id}/status` - Atualizar status
- `GET /api/admin/schedule` - Agenda administrativa
- `GET /api/admin/professionals` - Lista profissionais
- `GET /api/admin/settings` - Configurações do sistema
- `PUT /api/admin/settings` - Atualizar configurações

## 🛠️ Instalação

### Pré-requisitos
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache/Nginx) ou PHP built-in server

### Configuração

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/kelly/agendamento-system.git
   cd agendamento-system
   ```

2. **Configure o banco de dados:**
   ```bash
   # Execute o script SQL
   mysql -u root -p < database.sql
   ```

3. **Configure as credenciais:**
   ```bash
   # Copie e edite o arquivo de configuração
   cp config.env.example config.env
   # Edite as credenciais do banco
   ```

4. **Execute o servidor:**
   ```bash
   php -S localhost:8000
   ```

5. **Acesse a documentação:**
   ```
   http://localhost:8000/docs
   ```

## 📚 Documentação

A documentação completa da API está disponível em `/docs` e inclui:
- Exemplos de requisições
- Códigos de resposta
- Instruções de autenticação
- Parâmetros de entrada

## 🔐 Autenticação

Para endpoints administrativos, use a API Key:
```bash
# Header
curl -H "X-API-Key: agendamento_api_key_2024" http://localhost:8000/api/admin/schedule

# Query Parameter
curl "http://localhost:8000/api/admin/schedule?api_key=agendamento_api_key_2024"
```

## 🧪 Testes

Execute os testes automatizados:
```bash
php test_api.php
```

## 📁 Estrutura do Projeto

```
agendamento-system/
├── index.php                 # Ponto de entrada da API
├── config.env               # Configurações do banco
├── database.sql             # Script de criação das tabelas
├── config/
│   ├── Database.php         # Classe de conexão com banco
│   └── Auth.php            # Sistema de autenticação
├── controllers/
│   ├── ServiceController.php    # Controle de serviços
│   ├── SlotController.php       # Controle de horários
│   ├── AppointmentController.php # Controle de agendamentos
│   └── AdminController.php      # Controle administrativo
├── docs/
│   └── index.php            # Documentação da API
└── test_api.php             # Script de testes
```

## 🤝 Contribuição

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👨‍💻 Autor

**Kelly** - [@kelly](https://github.com/kelly)

## 📞 Suporte

Se você encontrar algum problema ou tiver dúvidas, abra uma [issue](https://github.com/kelly/agendamento-system/issues).

---

⭐ Se este projeto foi útil para você, considere dar uma estrela!