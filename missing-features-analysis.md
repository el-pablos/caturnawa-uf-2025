# Missing Features Analysis Report
**Caturnawa UNAS FEST 2025 - Laravel Application**  
**Analysis Date:** October 16, 2025  
**Analyzed Projects:** caturnawa2025 (Next.js), UNASFEST-2025 (Next.js)

---

## Executive Summary

This report documents missing features and content data found in reference projects that should be implemented in the current Laravel application to achieve feature parity and complete content coverage.

**Total Missing Features:** 8 major features  
**Total Content Data Items:** 6 categories  
**Estimated Implementation Time:** 40-60 hours

---

## Part 1: Missing Features from caturnawa2025

### 1. Rounds System (HIGH PRIORITY)
**Status:** ❌ Not Implemented  
**Complexity:** High  
**Estimated Time:** 12-16 hours

**Description:**  
The reference project has a comprehensive debate rounds system with multiple stages (preliminary, semifinal, final).

**Database Schema Found:**
```sql
model DebateRound {
  id            String        @id
  competitionId String
  stage         DebateStage   -- PRELIMINARY, SEMIFINAL, FINAL
  roundNumber   Int
  session       Int           -- Multiple sessions per round
  roundName     String
  startTime     DateTime
  endTime       DateTime
  location      String?
  matches       DebateMatch[]
}
```

**Required Implementation:**
- [ ] Create `debate_rounds` migration table
- [ ] Create `DebateRound` model with relationships
- [ ] Create admin CRUD for managing rounds
- [ ] Create public view for round schedules
- [ ] Add round assignment to registrations

**Benefits:**
- Better tournament organization
- Clear schedule for participants
- Automated bracket generation
- Professional tournament management

---

### 2. Match/Room System (HIGH PRIORITY)
**Status:** ❌ Not Implemented  
**Complexity:** High  
**Estimated Time:** 10-14 hours

**Description:**  
System for managing debate matches with room assignments, team pairings, and judge assignments.

**Database Schema Found:**
```sql
model DebateMatch {
  id            String      @id
  roundId       String
  matchNumber   Int
  room          String
  motion        String?
  team1Id       String?     -- Government team
  team2Id       String?     -- Opposition team
  team3Id       String?     -- Additional teams
  team4Id       String?
  judgeId       String?
  status        MatchStatus -- SCHEDULED, ONGOING, COMPLETED
  startTime     DateTime
  endTime       DateTime?
  scores        DebateScore[]
}
```

**Required Implementation:**
- [ ] Create `debate_matches` migration table
- [ ] Create `DebateMatch` model
- [ ] Create match scheduling system
- [ ] Create room assignment interface
- [ ] Add judge assignment functionality
- [ ] Create match results entry system

**Benefits:**
- Organized match scheduling
- Clear room assignments
- Judge allocation tracking
- Real-time match status updates

---

### 3. Team Standing/Leaderboard System (MEDIUM PRIORITY)
**Status:** ⚠️ Partially Implemented  
**Complexity:** Medium  
**Estimated Time:** 6-8 hours

**Description:**  
Advanced team standing system with victory points, speaker points, and ranking.

**Database Schema Found:**
```sql
model TeamStanding {
  id              String       @id
  registrationId  String       @unique
  totalMatches    Int          @default(0)
  wins            Int          @default(0)
  losses          Int          @default(0)
  draws           Int          @default(0)
  victoryPoints   Int          @default(0)
  speakerPoints   Float        @default(0)
  rank            Int?
  lastUpdated     DateTime     @updatedAt
}
```

**Current Status:**  
- ✅ Basic leaderboard exists
- ❌ No victory points calculation
- ❌ No speaker points tracking
- ❌ No automated ranking updates

**Required Implementation:**
- [ ] Add victory_points and speaker_points columns to leaderboard
- [ ] Create automated ranking calculation
- [ ] Add match result processing
- [ ] Create standing update triggers

---

### 4. Advanced Scoring System (MEDIUM PRIORITY)
**Status:** ⚠️ Partially Implemented  
**Complexity:** Medium  
**Estimated Time:** 8-10 hours

**Description:**  
Detailed scoring system for debate competitions with multiple criteria.

**Database Schema Found:**
```sql
model DebateScore {
  id              String   @id
  matchId         String
  participantId   String
  role            String   -- PM, LO, DPM, DLO, etc.
  contentScore    Float
  styleScore      Float
  strategyScore   Float
  totalScore      Float
  feedback        String?
  judgeId         String?
}
```

**Current Status:**  
- ✅ Basic scoring exists
- ❌ No role-based scoring
- ❌ No detailed criteria breakdown
- ❌ No speaker-specific scores

**Required Implementation:**
- [ ] Add role field to scores table
- [ ] Add detailed criteria columns
- [ ] Create role-based scoring interface
- [ ] Add speaker performance tracking

---

### 5. Payment Phase System (LOW PRIORITY)
**Status:** ❌ Not Implemented  
**Complexity:** Low  
**Estimated Time:** 4-6 hours

**Description:**  
Multi-phase pricing system (Early Bird, Phase 1, Phase 2) with different deadlines.

**Database Schema Found:**
```sql
enum PaymentPhase {
  EARLY_BIRD
  PHASE_1
  PHASE_2
}

-- In Competition model:
earlyBirdPrice     Int
phase1Price        Int
phase2Price        Int
earlyBirdStart     DateTime
earlyBirdEnd       DateTime
phase1Start        DateTime
phase1End          DateTime
phase2Start        DateTime
phase2End          DateTime
```

**Required Implementation:**
- [ ] Add payment phase columns to competitions table
- [ ] Create automatic phase detection logic
- [ ] Update registration pricing calculation
- [ ] Add phase display in competition details

---

## Part 2: Content Data from UNASFEST-2025

### 1. FAQ Data (HIGH PRIORITY)
**Status:** ❌ Not Implemented  
**Complexity:** Low  
**Estimated Time:** 2-3 hours

**Data Found:**
```typescript
[
  {
    question: "How do I register for the competition?",
    answer: "Visit caturnawa.unasfest.com, choose competition, click Register Now..."
  },
  {
    question: "How much is the registration fee?",
    answer: "Fees vary from Rp 300,000 to Rp 550,000"
  },
  {
    question: "What is the registration deadline?",
    answer: "Deadline for KDBI is August 30, 2025"
  },
  {
    question: "When will winners be announced?",
    answer: "Winners announced on October 17, 2025"
  },
  // ... 6 total FAQ items
]
```

**Required Implementation:**
- [ ] Create `faqs` migration table
- [ ] Create `Faq` model
- [ ] Create `FaqSeeder` with data
- [ ] Update FAQ page to use database data
- [ ] Add admin CRUD for FAQ management

---

### 2. Competition Timeline Data (HIGH PRIORITY)
**Status:** ❌ Not Implemented  
**Complexity:** Low  
**Estimated Time:** 3-4 hours

**Data Found:**
```typescript
// Timeline for each competition (IDC, EDC, SPC, Infografis, ShortVideo)
[
  { month: "AUGUST", day: "25-31", year: "2025", title: "Registration - Early Bird" },
  { month: "SEPTEMBER", day: "1-13", year: "2025", title: "Registration - Phase 1" },
  { month: "SEPTEMBER", day: "14-26", year: "2025", title: "Registration - Phase 2" },
  { month: "SEPTEMBER", day: "27", year: "2025", title: "Webinar and Technical Meeting" },
  { month: "OCTOBER", day: "13", year: "2025", title: "Preliminary Round - Day 1" },
  { month: "OCTOBER", day: "14", year: "2025", title: "Preliminary Round - Day 2" },
  { month: "OCTOBER", day: "15", year: "2025", title: "Semifinal Debate" },
  { month: "OCTOBER", day: "27", year: "2025", title: "Final Round" },
  { month: "NOVEMBER", day: "10", year: "2025", title: "Award Ceremony" },
]
```

**Required Implementation:**
- [ ] Create `competition_timelines` migration table
- [ ] Create `CompetitionTimeline` model
- [ ] Create `CompetitionTimelineSeeder` with data for all competitions
- [ ] Update timeline page to use database data
- [ ] Add admin CRUD for timeline management

---

### 3. Contact Information (MEDIUM PRIORITY)
**Status:** ⚠️ Partially Implemented  
**Complexity:** Low  
**Estimated Time:** 2 hours

**Data Needed:**
- Official email: contact@unasfest.com
- WhatsApp: +62 812-3456-7890
- Instagram: @unasfest
- TikTok: @unasfest
- YouTube: UNAS FEST Official
- Address: Universitas Nasional, Jakarta

**Required Implementation:**
- [ ] Create `contact_information` migration table
- [ ] Create `ContactInformation` model
- [ ] Create `ContactInformationSeeder`
- [ ] Update contact page with database data

---

### 4. Sponsors/Partners Data (MEDIUM PRIORITY)
**Status:** ❌ Not Implemented  
**Complexity:** Low  
**Estimated Time:** 3-4 hours

**Required Implementation:**
- [ ] Create `sponsors` migration table
- [ ] Create `Sponsor` model
- [ ] Create `SponsorSeeder`
- [ ] Add sponsor display section on homepage
- [ ] Add admin CRUD for sponsor management

---

### 5. Terms & Conditions Content (LOW PRIORITY)
**Status:** ⚠️ View exists but no database
**Complexity:** Low  
**Estimated Time:** 2 hours

**Required Implementation:**
- [ ] Create `terms_and_conditions` migration table
- [ ] Create `TermsAndConditions` model
- [ ] Create `TermsAndConditionsSeeder`
- [ ] Update terms page to use database data

---

### 6. Competition Descriptions (LOW PRIORITY)
**Status:** ✅ Already Implemented  
**Complexity:** N/A  
**Estimated Time:** 0 hours

**Current Status:**  
Already implemented via `competition_descriptions` table and `CompetitionDescription` model.

---

## Implementation Priority Matrix

| Feature | Priority | Complexity | Time | Impact | Status |
|---------|----------|------------|------|--------|--------|
| Rounds System | HIGH | High | 12-16h | High | ❌ |
| Match/Room System | HIGH | High | 10-14h | High | ❌ |
| FAQ Data | HIGH | Low | 2-3h | Medium | ❌ |
| Competition Timeline | HIGH | Low | 3-4h | Medium | ❌ |
| Team Standing | MEDIUM | Medium | 6-8h | Medium | ⚠️ |
| Advanced Scoring | MEDIUM | Medium | 8-10h | Medium | ⚠️ |
| Contact Info | MEDIUM | Low | 2h | Low | ⚠️ |
| Sponsors/Partners | MEDIUM | Low | 3-4h | Low | ❌ |
| Payment Phases | LOW | Low | 4-6h | Low | ❌ |
| Terms & Conditions | LOW | Low | 2h | Low | ⚠️ |

---

## Recommended Implementation Order

### Phase 1: Quick Wins (Content Data) - 10-15 hours
1. FAQ Data Seeder
2. Competition Timeline Seeder
3. Contact Information Seeder
4. Terms & Conditions Seeder
5. Sponsors Seeder

### Phase 2: Core Features - 25-35 hours
6. Rounds System
7. Match/Room System
8. Team Standing Enhancement
9. Advanced Scoring System

### Phase 3: Nice-to-Have - 4-6 hours
10. Payment Phase System

---

## Conclusion

**Total Features to Implement:** 10  
**Total Estimated Time:** 40-60 hours  
**Immediate Action Items:** Implement Phase 1 (Content Data Seeders) first for quick value delivery.

**Next Steps:**
1. Create database seeders for content data (Phase 1)
2. Run seeders to populate database
3. Update views to display seeded content
4. Plan Phase 2 implementation (Rounds & Match System)

