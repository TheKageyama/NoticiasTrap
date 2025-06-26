# 📰 Portal de Notícias Cultura Underground

**Um portal moderno sobre cultura urbana e arte alternativa**  
*Tema: Estilo grafite com elementos neon em preto profundo*

## 🚀 Tecnologias Utilizadas
- **PHP** (Backend com Mysql)
- **MySQL** (Banco de dados)
- **HTML5** (Estrutura)
- **CSS3** (Estilo grafite/neon)
- **JavaScript** (Interações básicas)

## ✨ Funcionalidades Principais
✅ **Sistema completo de autenticação**  
✅ **CRUD de notícias com upload de imagens**  
✅ **Dashboard personalizado**  
✅ **Design responsivo com tema grafite neon**  
✅ **Sistema de categorias**  
✅ **Proteção contra XSS/SQL Injection**

## 🛠️ Instalação

### Pré-requisitos
- Servidor web (XAMPP)
- PHP 7.4+
- MySQL 5.7+
- Git (opcional)

### Passo a Passo
1. Clone o repositório:
   ```bash
   git clone https://github.com/seu-usuario/cultura-underground.git
   ```
2. Importe o banco de dados:
   ```bash
   mysql -u root -p < dump.sql
   ```
3. Configure a conexão:
   ```php
   // includes/conexao.php
   $host = 'localhost';
   $dbname = 'cultura_underground';
   $username = 'root';
   $password = '';
   ```
4. Crie a pasta de uploads:
   ```bash
   mkdir assets/uploads
   chmod 755 assets/uploads
   ```

## 🎨 Estilo Visual
- **Cores predominantes**: Preto profundo (#0a0a0a), Neon vermelho (#ff073a)
- **Fontes**: Urbanist (corpo) + Press Start 2P (títulos)
- **Efeitos**: Sombras neon, bordas brilhantes

## 📂 Estrutura de Arquivos
```
cultura-underground/
├── assets/
│   ├── css/          # Estilos customizados
│   ├── uploads/      # Imagens enviadas
│   └── fonts/        # Fontes personalizadas
├── includes/         # Arquivos PHP essenciais
├── public/           # Páginas acessíveis
└── admin/            # Área restrita
```



## 👥 Contribuição
1. Faça um fork do projeto
2. Crie sua branch (`git checkout -b feature/nova-feature`)
3. Commit suas mudanças (`git commit -m 'Add nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request

## 📄 Licença
Este projeto está sob licença MIT - veja [LICENSE.md](LICENSE.md) para detalhes

---

**Desenvolvido com 💜 por Davi Schinoff**  
✉️ Contato: davi.schinoff@gmail.com  
🌐 Instagram: [www.instagram.com.br/davi_schinoff)

> "A arte nasce nas ruas e se transforma em revolução digital" - Central Cee



### 📌 Checklist Pós-Instalação
- [ ] Configurar conexão com banco de dados
- [ ] Testar upload de imagens
- [ ] Verificar redirecionamentos
- [ ] Personalizar informações do site
- [ ] Configurar e-mail para contato

**Atualizado em:** 24/06/2024  
**Versão:** 2.0.0 (Neon Grafite Edition)
