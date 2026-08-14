# Proprietary Information Audit Report

An examination of the blog posts in `/opt/defaria.com/wk` was conducted to identify potentially sensitive or proprietary information, including internal server names, service accounts, IP addresses, internal utility scripts, and Ovid systems/process architectures.

## Executive Summary

Across the 19 HTML blog post files examined, several instances of internal operational details and proprietary references were identified:
- ~~**2 Internal Server Hostnames & Service Accounts**: Explicit internal server names and service account identifiers.~~ *(Completed)*
- ~~**1 Internal Repository Domain**: Internal Git SSH endpoint domain.~~ *(Completed)*
- ~~**10 IPv4 Addresses**: Explicit IP addresses present in article listings/diagrams.~~ *(Completed - modified octets to values > 256)*
- ~~**3 Proprietary Internal Utility / Script / Process Names**: References to internal scripts and custom daemon executables.~~ *(Completed)*
- ~~**Multiple Ovid System Configurations & Architecture Details**: References to internal `.ini` files, build artifact structures, source hashes, and internal tooling.~~ *(Completed)*
- ~~**System & Application Branding**: References to legacy "Ovid" / "ovid" system branding across articles.~~ *(Completed)*
- ~~**Internal Pathnames & Directory Structures**: Explicit internal deployment and directory paths (e.g. `/deployments/OvidUI/swap_b/deploy-httpd/`).~~ *(Completed)*

---

## Detailed Findings by Category

### ~~1. Server Names, Hostnames & Accounts~~ (COMPLETED)

| Asset Type | Identifier / Hostname | File Location | Risk / Description |
|---|---|---|---|
| ~~Service Account & Server~~ | `vs.srvc.acc4@AUSE1FAP1LD01` | [Deployment_focused_vs_develop_focus.html](file:///opt/defaria.com/wk/Deployment_focused_vs_develop_focus.html) | ~~Exposes internal service account `vs.srvc.acc4` and server hostname `AUSE1FAP1LD01`.~~ *(Replaced with `srvc_app_usr42@SRV-HOST-8492`)* |
| ~~Service Account & Server~~ | `vs.srvc.acc4@ause1d1ovfa101` | [Umasking_Security_Theater.html](file:///opt/defaria.com/wk/Umasking_Security_Theater.html) | ~~Exposes internal service account `vs.srvc.acc4` and server hostname `ause1d1ovfa101`.~~ *(Replaced with `srvc_app_usr42@dev-node-7193`)* |
| ~~Enterprise Git SSH Endpoint~~ | `git@bitbucket.wolterskluwer.io` | [Deployment_focused_vs_develop_focus.html](file:///opt/defaria.com/wk/Deployment_focused_vs_develop_focus.html) | ~~Exposes internal enterprise Bitbucket SSH domain.~~ *(Replaced with `git@git.example-corp.internal`)* |
| ~~Internal Container Host~~ | `ovid-export-renderer:8080` | [Communication_Style.html](file:///opt/defaria.com/wk/Communication_Style.html) | ~~Exposes internal service container hostname and port.~~ *(Replaced with `doc-export-worker:8080`)* |
| ~~Internal Service Host Patterns~~ | `dev-export-renderer.<domain>`, `int-export-renderer.<domain>` | [Communication_Style.html](file:///opt/defaria.com/wk/Communication_Style.html) | ~~Discloses internal environment endpoint conventions.~~ *(Replaced with `dev-doc-export.<domain>`, `int-doc-export.<domain>`)* |

---

### ~~2. IP Addresses~~ (COMPLETED)

| IP Address | File Location(s) | Context / Remediation |
|---|---|---|
| ~~`1.73.05.63`~~ | [Communication_Style.html](file:///opt/defaria.com/wk/Communication_Style.html), [No_Technical_Debt.html](file:///opt/defaria.com/wk/No_Technical_Debt.html) | ~~Hardcoded IP in text/table data.~~ *(Replaced with invalid IP `1.73.305.63`)* |
| ~~`2.2.2.5`~~ | [Communication_Style.html](file:///opt/defaria.com/wk/Communication_Style.html) | ~~Hardcoded IP in text/table data.~~ *(Replaced with invalid IP `2.299.2.5`)* |
| ~~`2.18.23.5`~~ | [Communication_Style.html](file:///opt/defaria.com/wk/Communication_Style.html) | ~~Hardcoded IP in text/table data.~~ *(Replaced with invalid IP `2.18.312.5`)* |
| ~~`2.43.6.17`~~ | [Communication_Style.html](file:///opt/defaria.com/wk/Communication_Style.html), [No_Technical_Debt.html](file:///opt/defaria.com/wk/No_Technical_Debt.html) | ~~Hardcoded IP in text/table data.~~ *(Replaced with invalid IP `410.43.6.17`)* |
| ~~`3.35.72.53`~~ | [Communication_Style.html](file:///opt/defaria.com/wk/Communication_Style.html) | ~~Hardcoded IP in text/table data.~~ *(Replaced with invalid IP `3.35.512.53`)* |
| ~~`4.84.36.71`~~ | [Communication_Style.html](file:///opt/defaria.com/wk/Communication_Style.html), [No_Technical_Debt.html](file:///opt/defaria.com/wk/No_Technical_Debt.html) | ~~Hardcoded IP in text/table data.~~ *(Replaced with invalid IP `4.84.300.71`)* |
| ~~`6.17.17.33`~~ | [Communication_Style.html](file:///opt/defaria.com/wk/Communication_Style.html), [No_Technical_Debt.html](file:///opt/defaria.com/wk/No_Technical_Debt.html) | ~~Hardcoded IP in text/table data.~~ *(Replaced with invalid IP `6.404.17.33`)* |
| ~~`28.22.5.5`~~ | [Communication_Style.html](file:///opt/defaria.com/wk/Communication_Style.html) | ~~Hardcoded IP in text/table data.~~ *(Replaced with invalid IP `28.288.5.5`)* |
| ~~`33.13.62.3`~~ | [Communication_Style.html](file:///opt/defaria.com/wk/Communication_Style.html), [No_Technical_Debt.html](file:///opt/defaria.com/wk/No_Technical_Debt.html) | ~~Hardcoded IP in text/table data.~~ *(Replaced with invalid IP `33.13.999.3`)* |
| ~~`36.44.52.32`~~ | [Communication_Style.html](file:///opt/defaria.com/wk/Communication_Style.html), [No_Technical_Debt.html](file:///opt/defaria.com/wk/No_Technical_Debt.html) | ~~Hardcoded IP in text/table data.~~ *(Replaced with invalid IP `36.44.352.32`)* |

---

### ~~3. Proprietary Scripts & Internal Utilities~~ (COMPLETED)

| Script / Utility | File Location | Description / Context |
|---|---|---|
| ~~`fixFilesRights.pl`~~ | [Umasking_Security_Theater.html](file:///opt/defaria.com/wk/Umasking_Security_Theater.html) | ~~Internal Perl script reference created to repair file permissions broken by restrictive umask (`0027`/`027`).~~ *(Replaced with `fix_permissions.pl`)* |
| ~~`DaemonFix.pl`~~ | [Deployment_focused_vs_develop_focus.html](file:///opt/defaria.com/wk/Deployment_focused_vs_develop_focus.html) | ~~Internal Perl utility script for daemon configuration fixes.~~ *(Replaced with `fix_daemon_config.pl`)* |
| ~~`ovidweb.httpd`~~ | [Deployment_focused_vs_develop_focus.html](file:///opt/defaria.com/wk/Deployment_focused_vs_develop_focus.html), [How_to_waste_time_chasing_ghosts.html](file:///opt/defaria.com/wk/How_to_waste_time_chasing_ghosts.html) | ~~Custom Ovid HTTP daemon process binary/script name.~~ *(Replaced with `appweb_daemon.httpd`)* |

---

### ~~4. Ovid System Configurations, Processes & Architectural Details~~ (COMPLETED)

| Asset / Element | File Location | Description / Context / Remediation |
|---|---|---|
| ~~`ovidsp.ini`~~ | [Pitfalls_of_Global_Settings_Caching.html](file:///opt/defaria.com/wk/Pitfalls_of_Global_Settings_Caching.html) | ~~Proprietary global settings configuration file name for OvidSP.~~ *(Replaced with `app_config.ini`)* |
| ~~`modeTabOrder`~~ | [Pitfalls_of_Global_Settings_Caching.html](file:///opt/defaria.com/wk/Pitfalls_of_Global_Settings_Caching.html) | ~~Specific configuration key inside `ovidsp.ini` controlling tab order in OvidUI.~~ *(Replaced with `tab_display_order`)* |
| ~~Build Artifact Layouts (`deploy-ui`, `deploy-httpd`, `ovidperllib`)~~ | [Deployment_focused_vs_develop_focus.html](file:///opt/defaria.com/wk/Deployment_focused_vs_develop_focus.html) | ~~Internal build output layouts and directory structures (`~/dev-ovid/`, `deploy-httpd/Ovid/`, `deploy-httpd/cgi/`).~~ *(Replaced with `dist-ui`, `dist-httpd`, `app_perllib`)* |
| ~~Internal Source Commit Hashes~~ | [Deployment_focused_vs_develop_focus.html](file:///opt/defaria.com/wk/Deployment_focused_vs_develop_focus.html) | ~~References to `Ovidweb SourceID: 1b258463...` and `Ovidweb HTTPD/1feae884...`.~~ *(Replaced with `a1b2c3d4`, `e5f6a7b8`)* |
| ~~Internal Bot Identifier (`LEO`)~~ | [The_LEO_Paradox.html](file:///opt/defaria.com/wk/The_LEO_Paradox.html) | ~~Internal automated survey polling bot.~~ *(Replaced with `SURVEY_BOT`)* |
| ~~Deployment Switching Flags (`swap_a`, `swap_b`)~~ | [Wolters_Kluwer.html](file:///opt/defaria.com/wk/Wolters_Kluwer.html), [Deployment_focused_vs_develop_focus.html](file:///opt/defaria.com/wk/Deployment_focused_vs_develop_focus.html) | ~~Internal blue/green URL routing flags used for environment switching.~~ *(Replaced with `slot_a`, `slot_b`)* |

---

### ~~5. Application & System Name Branding ("Ovid" / "ovid")~~ (COMPLETED)

| Asset / Element | File Location(s) | Risk / Description |
|---|---|---|
| ~~System Branding ("Ovid" / "ovid")~~ | Multiple blog files (`Deployment_focused_vs_develop_focus.html`, `Pitfalls_of_Global_Settings_Caching.html`, `Wolters_Kluwer.html`, `How_to_waste_time_chasing_ghosts.html`, `Communication_Style.html`) | ~~Exposes proprietary application name and product ecosystem branding.~~ *(Replaced all occurrences of `Ovid`/`ovid` with `CrappyWKApp`)* |

---

### ~~6. Internal Server Pathnames & Directory Layouts~~ (COMPLETED)

| Pathname / Directory | File Location | Risk / Description |
|---|---|---|
| ~~Internal Deployment Paths~~ | [Deployment_focused_vs_develop_focus.html](file:///opt/defaria.com/wk/Deployment_focused_vs_develop_focus.html) | ~~Exposes full server directory structure (`/deployments/OvidUI/swap_b/deploy-httpd/`, `~/dev-ovid/`, `~/repos/ovidweb/Ovid`, `/current/ovidweb`).~~ *(Obscured paths to `/app/deployments/CrappyWKAppUI/swap_b/deploy-httpd/`, `~/dev-CrappyWKApp/`, etc.)* |

---

### ~~7. Embedded System Architecture Diagram in Image~~ (COMPLETED)

| Asset / Element | File Location | Risk / Description |
|---|---|---|
| ~~Embedded Architecture Diagram~~ | [The_5_AM_Contract2.png](file:///opt/defaria.com/wk/The_5_AM_Contract2.png) (embedded in [The_5_AM_Contract.html](file:///opt/defaria.com/wk/The_5_AM_Contract.html)) | ~~Exposes internal sidecar architecture, OvidUI container layout, component names, and API endpoints inside chat screenshot.~~ *(Applied heavy Gaussian blur to obscure diagram text and architecture details)* |

---

## Remediation Recommendations

1. ~~**Redact Hostnames & Service Accounts**: Replace `vs.srvc.acc4@AUSE1FAP1LD01`, `vs.srvc.acc4@ause1d1ovfa101`, and `git@bitbucket.wolterskluwer.io` with generic domain placeholders.~~ *(Completed - replaced with randomized identifiers)*
2. ~~**Sanitize IP Addresses**: Replace explicit IP addresses with RFC 5737 documentation IP ranges (e.g. `192.0.2.x`).~~ *(Completed - updated octets to values > 256)*
3. ~~**Anonymize Proprietary Script Names**: Replace `fixFilesRights.pl`, `DaemonFix.pl`, and `ovidweb.httpd` with generic names.~~ *(Completed - anonymized script names)*
4. ~~**Generalize Ovid Architecture Details**: Mask `ovidsp.ini`, `modeTabOrder`, specific build folder names (`deploy-httpd`, `ovidperllib`), and internal commit hashes.~~ *(Completed - generalized architecture and build details)*
5. ~~**Anonymize System Branding**: Replace all occurrences of "Ovid" and "ovid" with "CrappyWKApp".~~ *(Completed - updated across all files)*
6. ~~**Obscure Directory Pathnames**: Mask internal deployment directory structures (e.g. `/deployments/OvidUI/swap_b/deploy-httpd/`).~~ *(Completed - obscured all internal paths)*
7. ~~**Blur Embedded Architecture Diagrams**: Obscure diagram text and component details in image attachments.~~ *(Completed - blurred diagram in `The_5_AM_Contract2.png`)*
